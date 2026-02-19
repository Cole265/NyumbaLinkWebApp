<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenancy;
use App\Models\Property;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    /**
     * Get tenant's active tenancies (rented properties)
     */
    public function myTenancies(Request $request)
    {
        $user = $request->user();

        $tenancies = Tenancy::with([
            'property.primaryImage',
            'property.landlord.user',
            'landlord.user',
        ])
        ->where('tenant_id', $user->id)
        ->where('status', 'active')
        ->orderBy('start_date', 'desc')
        ->get();

        // Add computed fields
        $tenancies->transform(function ($tenancy) {
            $tenancy->property->primary_image_url = $tenancy->property->primaryImage 
                ? asset('storage/' . $tenancy->property->primaryImage->image_path)
                : null;
            $tenancy->landlord_name = $tenancy->landlord->user->name;
            return $tenancy;
        });

        return response()->json([
            'success' => true,
            'data' => $tenancies,
        ]);
    }

    /**
     * Rate property and mark as vacated
     */
    public function vacateAndRate(Request $request, $tenancyId)
    {
        $user = $request->user();
        
        $tenancy = Tenancy::with(['property', 'property.landlord'])
            ->findOrFail($tenancyId);

        // Ensure this tenancy belongs to the authenticated tenant
        if ($tenancy->tenant_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to vacate this tenancy.',
            ], 403);
        }

        // Ensure tenancy is still active
        if ($tenancy->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This tenancy is no longer active.',
            ], 422);
        }

        $validated = $request->validate([
            'communication_rating' => 'required|integer|min:1|max:5',
            'accuracy_rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'professionalism_rating' => 'required|integer|min:1|max:5',
            'fairness_rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Get or create tenant profile
            $tenantProfile = $user->tenantProfile;
            if (!$tenantProfile) {
                $tenantProfile = \App\Models\TenantProfile::create(['user_id' => $user->id]);
            }

            // Create or update rating (one rating per landlord/tenant/property)
            $rating = Rating::updateOrCreate(
                [
                    'landlord_id' => $tenancy->property->landlord_id,
                    'tenant_id' => $tenantProfile->id,
                    'property_id' => $tenancy->property_id,
                ],
                [
                    'communication_rating' => $validated['communication_rating'],
                    'accuracy_rating' => $validated['accuracy_rating'],
                    'cleanliness_rating' => $validated['cleanliness_rating'],
                    'professionalism_rating' => $validated['professionalism_rating'],
                    'fairness_rating' => $validated['fairness_rating'],
                    'review' => $validated['review'] ?? null,
                ]
            );

            // Mark tenancy as vacated
            $tenancy->markAsVacated();

            // Mark property as draft/vacant and deactivate listing.
            // Landlord will explicitly list it again (and pay listing fee) when ready.
            $tenancy->property->update(['status' => 'draft']);

            if ($tenancy->property->listing) {
                $tenancy->property->listing->update([
                    'is_active' => false,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Property marked as vacated and rated successfully. The property is now vacant and can be listed again by the landlord.',
                'data' => [
                    'tenancy' => $tenancy->fresh(),
                    'rating' => $rating,
                    'property' => $tenancy->property->fresh(),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Failed to vacate property', [
                'tenancy_id' => $tenancyId,
                'tenant_id' => optional($user)->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to vacate property',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
