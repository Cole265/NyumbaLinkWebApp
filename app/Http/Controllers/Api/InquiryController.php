<?php
// app/Http/Controllers/Api/InquiryController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\Property;
use App\Models\TenantProfile;
use Illuminate\Http\Request;
use App\Notifications\NewInquiryReceived;
use App\Notifications\InquiryResponded;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{
    /**
     * Tenant creates an inquiry for a property
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Validate request
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'message' => 'required|string|min:10|max:500',
        ]);

        // Get the property and its active listing
        $property = Property::with('listing')->findOrFail($validated['property_id']);

        // Check if property is published
        if ($property->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'This property is not available for inquiries',
            ], 422);
        }

        // Check if listing exists and is active
        if (!$property->listing || !$property->listing->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'This property listing has expired',
            ], 422);
        }

        // Prevent landlord from inquiring about their own property
        if ($property->landlord->user_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot inquire about your own property',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create inquiry
            $inquiry = Inquiry::create([
                'listing_id' => $property->listing->id,
                'tenant_id' => $user->id,
                'message' => $validated['message'],
                'status' => 'pending',
            ]);

            // Increment inquiry count on listing
            $property->listing->increment('inquiry_count');

            DB::commit();

            $property->landlord->user->notify(new NewInquiryReceived(
               $inquiry,
               $property,
               $user
            ));

            // Load relationships
            $inquiry->load(['listing.property', 'tenant']);

            // Get landlord contact info for tenant to contact via WhatsApp
            $landlordContact = [
                'name' => $property->landlord->user->name,
                'phone' => $property->landlord->user->phone,
                'whatsapp_link' => 'https://wa.me/' . str_replace(['+', ' '], '', $property->landlord->user->phone),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Inquiry sent successfully! You can now contact the landlord via WhatsApp.',
                'data' => [
                    'inquiry' => $inquiry,
                    'landlord_contact' => $landlordContact,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send inquiry',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all inquiries for tenant
     */
    public function tenantInquiries(Request $request)
    {
        $user = $request->user();

        $inquiries = Inquiry::with([
            'listing.property.primaryImage',
            'listing.property.landlord.user',
        ])
        ->where('tenant_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        // Add computed fields
        $inquiries->getCollection()->transform(function ($inquiry) {
            $property = $inquiry->listing->property;
            
            $inquiry->property_title = $property->title;
            $inquiry->property_price = $property->price;
            $inquiry->property_city = $property->city;
            $inquiry->property_image = $property->primaryImage 
                ? asset('storage/' . $property->primaryImage->image_path)
                : null;
            
            $inquiry->landlord_name = $property->landlord->user->name;
            $inquiry->landlord_phone = $property->landlord->user->phone;
            $inquiry->whatsapp_link = 'https://wa.me/' . str_replace(['+', ' '], '', $property->landlord->user->phone);
            
            return $inquiry;
        });

        return response()->json([
            'success' => true,
            'data' => $inquiries,
        ]);
    }

    /**
     * Get all inquiries for landlord (for their properties)
     */
    public function landlordInquiries(Request $request)
    {
        $landlord = $request->user()->landlordProfile;

        $inquiries = Inquiry::with([
            'tenant',
            'listing.property.primaryImage',
        ])
        ->whereHas('listing.property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })
        ->orderBy('status', 'asc') // Pending first
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        // Add computed fields
        $inquiries->getCollection()->transform(function ($inquiry) {
            $property = $inquiry->listing->property;
            
            $inquiry->property_title = $property->title;
            $inquiry->property_price = $property->price;
            $inquiry->property_image = $property->primaryImage 
                ? asset('storage/' . $property->primaryImage->image_path)
                : null;
            
            $inquiry->tenant_name = $inquiry->tenant->name;
            $inquiry->tenant_phone = $inquiry->tenant->phone;
            $inquiry->tenant_email = $inquiry->tenant->email;
            $inquiry->whatsapp_link = 'https://wa.me/' . str_replace(['+', ' '], '', $inquiry->tenant->phone);
            
            return $inquiry;
        });

        return response()->json([
            'success' => true,
            'data' => $inquiries,
            'stats' => [
                'total' => $inquiries->total(),
                'pending' => Inquiry::whereHas('listing.property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->where('status', 'pending')->count(),
                'responded' => Inquiry::whereHas('listing.property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->where('status', 'responded')->count(),
            ],
        ]);
    }

    /**
     * Landlord marks inquiry as responded
     */
    public function respond(Request $request, $id)
    {
        $landlord = $request->user()->landlordProfile;

        $inquiry = Inquiry::with('listing.property')
            ->findOrFail($id);

        // Verify landlord owns this property
        if ($inquiry->listing->property->landlord_id !== $landlord->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $inquiry->markAsResponded();

        $inquiry->tenant->notify(new InquiryResponded(
           $inquiry,
           $inquiry->listing->property,
           $landlord
        ));

        return response()->json([
            'success' => true,
            'message' => 'Inquiry marked as responded',
            'data' => $inquiry,
        ]);
    }

    /**
     * Landlord marks inquiry as closed
     */
    public function close(Request $request, $id)
    {
        $landlord = $request->user()->landlordProfile;

        $inquiry = Inquiry::with('listing.property')
            ->findOrFail($id);

        // Verify landlord owns this property
        if ($inquiry->listing->property->landlord_id !== $landlord->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $inquiry->markAsClosed();

        return response()->json([
            'success' => true,
            'message' => 'Inquiry marked as closed',
            'data' => $inquiry,
        ]);
    }

    /**
     * Landlord deletes an inquiry (for their property)
     */
    public function destroy(Request $request, $id)
    {
        $landlord = $request->user()->landlordProfile;

        $inquiry = Inquiry::with('listing.property')->findOrFail($id);

        if ($inquiry->listing->property->landlord_id !== $landlord->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $inquiry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inquiry deleted.',
        ]);
    }

    /**
     * Get tenant profile
     */
    public function tenantProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->tenantProfile;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'profile' => $profile,
            ],
        ]);
    }

    /**
     * Update tenant profile
     */
    public function updateTenantProfile(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
            'occupation' => 'sometimes|string|max:255',
            'preferences' => 'sometimes|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Update user info
            if (isset($validated['name']) || isset($validated['phone'])) {
                $user->update([
                    'name' => $validated['name'] ?? $user->name,
                    'phone' => $validated['phone'] ?? $user->phone,
                ]);
            }

            // Update tenant profile
            if (isset($validated['occupation']) || isset($validated['preferences'])) {
                $user->tenantProfile->update([
                    'occupation' => $validated['occupation'] ?? $user->tenantProfile->occupation,
                    'preferences' => $validated['preferences'] ?? $user->tenantProfile->preferences,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user->fresh(),
                    'profile' => $user->tenantProfile->fresh(),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get inquiry statistics for tenant
     */
    public function tenantStats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_inquiries' => Inquiry::where('tenant_id', $user->id)->count(),
            'pending' => Inquiry::where('tenant_id', $user->id)->where('status', 'pending')->count(),
            'responded' => Inquiry::where('tenant_id', $user->id)->where('status', 'responded')->count(),
            'properties_inquired' => Inquiry::where('tenant_id', $user->id)
                ->distinct('listing_id')
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}