<?php
// app/Http/Controllers/Api/PropertyController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyAmenity;
use App\Models\Listing;
use App\Models\PropertyBoost;
use App\Models\Transaction;
use App\Models\Tenancy;
use App\Models\User;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertyController extends Controller
{
    /**
     * Get all properties for authenticated landlord
     */
    public function index(Request $request)
    {
        $landlord = $request->user()->landlordProfile;

        $properties = Property::with(['images', 'amenities', 'listing', 'activeBoost'])
            ->where('landlord_id', $landlord->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Add computed fields
        $properties->getCollection()->transform(function ($property) {
            $property->is_boosted = $property->isBoosted();
            $property->primary_image_url = $property->primaryImage 
                ? asset('storage/' . $property->primaryImage->image_path)
                : null;
            return $property;
        });

        return response()->json([
            'success' => true,
            'data' => $properties,
        ]);
    }

    /**
     * Store a new property
     */
    public function store(Request $request)
    {
        $landlord = $request->user()->landlordProfile;

        // Validate request
        $validated = $request->validate([
            'property_type' => 'required|in:residential,land,commercial',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:MWK,USD',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'size_sqm' => 'nullable|numeric|min:0',
            'is_furnished' => 'boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        DB::beginTransaction();

        try {
            // Create property
            $property = Property::create([
                'landlord_id' => $landlord->id,
                'property_type' => $validated['property_type'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'area' => $validated['area'] ?? null,
                'price' => $validated['price'],
                'currency' => $validated['currency'],
                'bedrooms' => $validated['bedrooms'] ?? null,
                'bathrooms' => $validated['bathrooms'] ?? null,
                'size_sqm' => $validated['size_sqm'] ?? null,
                'is_furnished' => $validated['is_furnished'] ?? false,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'status' => 'draft', // Starts as draft
            ]);

            // Upload and save images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('properties', 'public');
                    
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0, // First image is primary
                        'order' => $index + 1,
                    ]);
                }
            }

            // Save amenities
            if (!empty($validated['amenities'])) {
                foreach ($validated['amenities'] as $amenity) {
                    PropertyAmenity::create([
                        'property_id' => $property->id,
                        'amenity' => $amenity,
                    ]);
                }
            }

            DB::commit();

            $property->load(['images', 'amenities']);

            return response()->json([
                'success' => true,
                'message' => 'Property created successfully as draft. Submit for review to publish.',
                'data' => $property,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create property',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single property details
     */
    public function show(Request $request, $id)
    {
        $landlord = $request->user()->landlordProfile;

        $property = Property::with(['images', 'amenities', 'listing', 'activeBoost', 'inquiries'])
            ->where('landlord_id', $landlord->id)
            ->findOrFail($id);

        $property->is_boosted = $property->isBoosted();
        $property->total_views = $property->listing?->view_count ?? 0;
        $property->total_inquiries = $property->listing?->inquiry_count ?? 0;
        $property->formatted_images = $property->images->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => asset('storage/' . $image->image_path),
                'is_primary' => $image->is_primary,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $property,
        ]);
    }

    /**
     * Update property
     */
    public function update(Request $request, $id)
    {
        $landlord = $request->user()->landlordProfile;

        $property = Property::where('landlord_id', $landlord->id)->findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'property_type' => 'sometimes|in:residential,land,commercial',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|min:50',
            'city' => 'sometimes|string|max:100',
            'district' => 'sometimes|string|max:100',
            'area' => 'nullable|string|max:100',
            'price' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|in:MWK,USD',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'size_sqm' => 'nullable|numeric|min:0',
            'is_furnished' => 'boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'new_images' => 'nullable|array|max:10',
            'new_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:property_images,id',
        ]);

        DB::beginTransaction();

        try {
            // Update property
            $property->update($validated);

            // Update amenities if provided
            if (isset($validated['amenities'])) {
                // Delete old amenities
                $property->amenities()->delete();
                
                // Add new amenities
                foreach ($validated['amenities'] as $amenity) {
                    PropertyAmenity::create([
                        'property_id' => $property->id,
                        'amenity' => $amenity,
                    ]);
                }
            }

            // Remove images if requested
            if (!empty($validated['remove_images'])) {
                $imagesToRemove = PropertyImage::whereIn('id', $validated['remove_images'])
                    ->where('property_id', $property->id)
                    ->get();

                foreach ($imagesToRemove as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            // Add new images if provided
            if ($request->hasFile('new_images')) {
                $currentMaxOrder = $property->images()->max('order') ?? 0;
                
                foreach ($request->file('new_images') as $index => $image) {
                    $path = $image->store('properties', 'public');
                    
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image_path' => $path,
                        'is_primary' => $property->images()->count() === 0 && $index === 0,
                        'order' => $currentMaxOrder + $index + 1,
                    ]);
                }
            }

            // If property was rejected, set back to draft
            if ($property->status === 'rejected') {
                $property->update(['status' => 'draft']);
            }

            DB::commit();

            $property->load(['images', 'amenities']);

            return response()->json([
                'success' => true,
                'message' => 'Property updated successfully',
                'data' => $property,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update property',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete property
     */
    public function destroy(Request $request, $id)
    {
        $landlord = $request->user()->landlordProfile;

        $property = Property::where('landlord_id', $landlord->id)->findOrFail($id);

        // Delete images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete property (cascade will handle related records)
        $property->delete();

        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully',
        ]);
    }

    /**
     * Submit property for review (draft → pending_review)
     */
    public function publish(Request $request, Property $property)
    {
        $landlord = $request->user()->landlordProfile;

        // Verify ownership
        if ($property->landlord_id !== $landlord->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Check if property has at least one image
        if ($property->images()->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Property must have at least one image before publishing',
            ], 422);
        }

        // Update status to pending review
        $property->update(['status' => 'pending_review']);

        return response()->json([
            'success' => true,
            'message' => 'Property submitted for review. Admin will approve it soon.',
            'data' => $property,
        ]);
    }

    /**
     * Boost property (premium feature)
     */
    public function boost(Request $request, Property $property)
    {
        $landlord = $request->user()->landlordProfile;

        // Verify ownership
        if ($property->landlord_id !== $landlord->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Validate boost type
        $validated = $request->validate([
            'boost_type' => 'required|in:7_day_boost,14_day_featured',
            'payment_method' => 'required|in:mobile_money,airtel_money,tnm_mpamba,bank_transfer,cash',
            'payment_phone' => 'required_if:payment_method,airtel_money,tnm_mpamba,mobile_money|nullable|string',
        ]);

        // Check if property is published
        if (!$property->isPublished()) {
            return response()->json([
                'success' => false,
                'message' => 'Only published properties can be boosted',
            ], 422);
        }

        // Calculate boost price
        $boostPrices = [
            '7_day_boost' => 3000,
            '14_day_featured' => 5000,
        ];

        $amount = $boostPrices[$validated['boost_type']];
        $duration = $validated['boost_type'] === '7_day_boost' ? 7 : 14;

        DB::beginTransaction();

        try {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'property_id' => $property->id,
                'transaction_type' => 'boost_fee',
                'amount' => $amount,
                'payment_method' => $validated['payment_method'],
                'reference_number' => 'KL-BOOST-' . strtoupper(uniqid()),
                'status' => 'pending', // In production, integrate with payment gateway
            ]);

            // Create boost (activate immediately for demo purposes)
            $boost = PropertyBoost::create([
                'property_id' => $property->id,
                'boost_type' => $validated['boost_type'],
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays($duration),
                'amount_paid' => $amount,
                'is_active' => true,
            ]);

            // In production, you would:
            // 1. Send payment request to mobile money API
            // 2. Wait for callback
            // 3. Activate boost only after payment confirmation

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Boost activated successfully',
                'data' => [
                    'boost' => $boost,
                    'transaction' => $transaction,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate boost',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark property as rented (assign tenant)
     */
    public function markAsRented(Request $request, Property $property)
    {
        $landlord = $request->user()->landlordProfile;

        // Verify landlord owns this property
        if ($property->landlord_id !== $landlord->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Check if property is already rented
        if ($property->status === 'rented' && $property->activeTenancy()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This property is already rented',
            ], 422);
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
        ]);

        // Verify tenant exists and is a tenant
        $tenant = User::findOrFail($validated['tenant_id']);
        if ($tenant->role !== 'tenant') {
            return response()->json([
                'success' => false,
                'message' => 'Selected user is not a tenant',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create tenancy record
            $tenancy = Tenancy::create([
                'property_id' => $property->id,
                'tenant_id' => $tenant->id,
                'landlord_id' => $landlord->id,
                'start_date' => $validated['start_date'],
                'status' => 'active',
            ]);

            // Update property status
            $property->update(['status' => 'rented']);

            // Deactivate listing
            if ($property->listing) {
                $property->listing->update(['is_active' => false]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Property marked as rented successfully',
                'data' => [
                    'property' => $property->fresh(['activeTenancy.tenant']),
                    'tenancy' => $tenancy->load('tenant'),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark property as rented',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all rented properties for landlord
     */
    public function rentedProperties(Request $request)
    {
        $landlord = $request->user()->landlordProfile;

        $properties = Property::with([
            'images',
            'primaryImage',
            'amenities',
            'listing',
            'activeTenancy.tenant',
        ])
        ->where('landlord_id', $landlord->id)
        ->where('status', 'rented')
        ->whereHas('activeTenancy')
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // Add computed fields
        $properties->getCollection()->transform(function ($property) {
            $property->primary_image_url = $property->primaryImage 
                ? asset('storage/' . $property->primaryImage->image_path)
                : null;
            
            // Load active tenancy if not already loaded
            if (!$property->relationLoaded('activeTenancy')) {
                $property->load('activeTenancy.tenant');
            }
            
            $property->tenant_name = $property->activeTenancy?->tenant?->name ?? null;
            $property->tenant_email = $property->activeTenancy?->tenant?->email ?? null;
            $property->tenant_phone = $property->activeTenancy?->tenant?->phone ?? null;
            $property->tenancy_start_date = $property->activeTenancy?->start_date?->format('Y-m-d') ?? null;
            return $property;
        });

        return response()->json([
            'success' => true,
            'data' => $properties,
        ]);
    }

    /**
     * Get inquiries for a specific property (for tenant selection)
     */
    public function propertyInquiries(Request $request, Property $property)
    {
        $landlord = $request->user()->landlordProfile;

        // Verify landlord owns this property
        if ($property->landlord_id !== $landlord->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $inquiries = Inquiry::with(['tenant'])
            ->whereHas('listing', function ($query) use ($property) {
                $query->where('property_id', $property->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $inquiries = $inquiries->map(function ($inquiry) {
            return [
                'id' => $inquiry->id,
                'tenant_id' => $inquiry->tenant_id,
                'tenant_name' => $inquiry->tenant->name,
                'tenant_email' => $inquiry->tenant->email,
                'tenant_phone' => $inquiry->tenant->phone,
                'message' => $inquiry->message,
                'status' => $inquiry->status,
                'created_at' => $inquiry->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $inquiries,
        ]);
    }
}