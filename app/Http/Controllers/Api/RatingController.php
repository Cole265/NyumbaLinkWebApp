<?php
// app/Http/Controllers/Api/RatingController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Property;
use App\Models\LandlordProfile;
use Illuminate\Http\Request;
use App\Notifications\NewRatingReceived;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    /**
     * Tenant creates a rating for a landlord
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $tenantProfile = $user->tenantProfile;

        // Validate request
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'communication_rating' => 'required|integer|min:1|max:5',
            'accuracy_rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'professionalism_rating' => 'required|integer|min:1|max:5',
            'fairness_rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        // Get property and landlord
        $property = Property::with('landlord')->findOrFail($validated['property_id']);
        $landlord = $property->landlord;

        // Prevent rating your own property
        if ($landlord->user_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot rate your own property',
            ], 422);
        }

        // Check if tenant has already rated this landlord for this property
        $existingRating = Rating::where('landlord_id', $landlord->id)
            ->where('tenant_id', $tenantProfile->id)
            ->where('property_id', $property->id)
            ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this landlord for this property',
                'existing_rating' => $existingRating,
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create rating (overall_rating is calculated automatically in the model)
            $rating = Rating::create([
                'landlord_id' => $landlord->id,
                'tenant_id' => $tenantProfile->id,
                'property_id' => $property->id,
                'communication_rating' => $validated['communication_rating'],
                'accuracy_rating' => $validated['accuracy_rating'],
                'cleanliness_rating' => $validated['cleanliness_rating'],
                'professionalism_rating' => $validated['professionalism_rating'],
                'fairness_rating' => $validated['fairness_rating'],
                'review' => $validated['review'] ?? null,
            ]);

            // The landlord's avg_rating is updated automatically via the Rating model's saved event

            DB::commit();

            $landlord->user->notify(new NewRatingReceived($rating, $property));

            // Load relationships
            $rating->load(['landlord.user', 'tenant.user', 'property']);

            return response()->json([
                'success' => true,
                'message' => 'Rating submitted successfully',
                'data' => $rating,
                'landlord_new_rating' => [
                    'avg_rating' => $landlord->fresh()->avg_rating,
                    'total_ratings' => $landlord->fresh()->total_ratings,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit rating',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all ratings submitted by the tenant
     */
    public function tenantRatings(Request $request)
    {
        $user = $request->user();
        $tenantProfile = $user->tenantProfile;

        $ratings = Rating::with([
            'landlord.user',
            'property.primaryImage',
        ])
        ->where('tenant_id', $tenantProfile->id)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        // Add computed fields
        $ratings->getCollection()->transform(function ($rating) {
            $rating->landlord_name = $rating->landlord->user->name;
            $rating->landlord_business = $rating->landlord->business_name;
            $rating->property_title = $rating->property->title;
            $rating->property_image = $rating->property->primaryImage 
                ? asset('storage/' . $rating->property->primaryImage->image_path)
                : null;
            
            return $rating;
        });

        return response()->json([
            'success' => true,
            'data' => $ratings,
        ]);
    }

    /**
     * Get a specific rating
     */
    public function show($id)
    {
        $rating = Rating::with([
            'landlord.user',
            'tenant.user',
            'property.primaryImage',
        ])->findOrFail($id);

        $rating->landlord_name = $rating->landlord->user->name;
        $rating->landlord_business = $rating->landlord->business_name;
        $rating->tenant_name = $rating->tenant->user->name;
        $rating->property_title = $rating->property->title;
        $rating->property_image = $rating->property->primaryImage 
            ? asset('storage/' . $rating->property->primaryImage->image_path)
            : null;

        return response()->json([
            'success' => true,
            'data' => $rating,
        ]);
    }

    /**
     * Get all ratings for a specific landlord (public)
     */
    public function landlordRatings($landlordId)
    {
        $landlord = LandlordProfile::with('user')->findOrFail($landlordId);

        $ratings = Rating::with([
            'tenant.user',
            'property',
        ])
        ->where('landlord_id', $landlord->id)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        // Add computed fields
        $ratings->getCollection()->transform(function ($rating) {
            $rating->tenant_name = $rating->tenant->user->name;
            $rating->property_title = $rating->property->title;
            
            // Hide tenant's full contact info for privacy
            unset($rating->tenant->user->email);
            unset($rating->tenant->user->phone);
            
            return $rating;
        });

        return response()->json([
            'success' => true,
            'data' => $ratings,
            'landlord_info' => [
                'name' => $landlord->user->name,
                'business_name' => $landlord->business_name,
                'avg_rating' => $landlord->avg_rating,
                'total_ratings' => $landlord->total_ratings,
                'verified' => $landlord->isVerified(),
            ],
        ]);
    }

    /**
     * Get ratings for the authenticated landlord (landlord dashboard)
     */
    public function myLandlordRatings(Request $request)
    {
        $landlord = $request->user()->landlordProfile;

        if (!$landlord) {
            return response()->json([
                'success' => false,
                'message' => 'Landlord profile not found',
            ], 404);
        }

        $ratings = Rating::with([
            'tenant.user',
            'property',
        ])
        ->where('landlord_id', $landlord->id)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        // Reuse the same transformation as landlordRatings
        $ratings->getCollection()->transform(function ($rating) {
            $rating->tenant_name = $rating->tenant->user->name;
            $rating->property_title = $rating->property->title;

            // Hide tenant's full contact info for privacy
            unset($rating->tenant->user->email);
            unset($rating->tenant->user->phone);

            return $rating;
        });

        return response()->json([
            'success' => true,
            'data' => $ratings,
            'landlord_info' => [
                'name' => $landlord->user->name,
                'business_name' => $landlord->business_name,
                'avg_rating' => $landlord->avg_rating,
                'total_ratings' => $landlord->total_ratings,
                'verified' => $landlord->isVerified(),
            ],
        ]);
    }

    /**
     * Get all ratings for a specific property (public, property details page)
     */
    public function propertyRatings($propertyId)
    {
        $ratings = Rating::with([
            'tenant.user',
            'landlord.user',
        ])
        ->where('property_id', $propertyId)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        $ratings->getCollection()->transform(function ($rating) {
            $rating->tenant_name = $rating->tenant->user->name;
            $rating->landlord_name = $rating->landlord->user->name;
            return $rating;
        });

        return response()->json([
            'success' => true,
            'data' => $ratings,
        ]);
    }

    /**
     * Get rating statistics for a landlord
     */
    public function landlordRatingStats($landlordId)
    {
        $landlord = LandlordProfile::findOrFail($landlordId);

        $ratings = Rating::where('landlord_id', $landlord->id)->get();

        if ($ratings->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'total_ratings' => 0,
                    'avg_rating' => 0,
                    'rating_breakdown' => [
                        '5_stars' => 0,
                        '4_stars' => 0,
                        '3_stars' => 0,
                        '2_stars' => 0,
                        '1_star' => 0,
                    ],
                    'category_averages' => [
                        'communication' => 0,
                        'accuracy' => 0,
                        'cleanliness' => 0,
                        'professionalism' => 0,
                        'fairness' => 0,
                    ],
                ],
            ]);
        }

        // Calculate rating breakdown
        $breakdown = [
            '5_stars' => $ratings->where('overall_rating', '>=', 4.5)->count(),
            '4_stars' => $ratings->whereBetween('overall_rating', [3.5, 4.49])->count(),
            '3_stars' => $ratings->whereBetween('overall_rating', [2.5, 3.49])->count(),
            '2_stars' => $ratings->whereBetween('overall_rating', [1.5, 2.49])->count(),
            '1_star' => $ratings->where('overall_rating', '<', 1.5)->count(),
        ];

        // Calculate category averages
        $categoryAverages = [
            'communication' => round($ratings->avg('communication_rating'), 2),
            'accuracy' => round($ratings->avg('accuracy_rating'), 2),
            'cleanliness' => round($ratings->avg('cleanliness_rating'), 2),
            'professionalism' => round($ratings->avg('professionalism_rating'), 2),
            'fairness' => round($ratings->avg('fairness_rating'), 2),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'total_ratings' => $ratings->count(),
                'avg_rating' => $landlord->avg_rating,
                'rating_breakdown' => $breakdown,
                'category_averages' => $categoryAverages,
            ],
        ]);
    }

    /**
     * Update a rating (tenant can edit their own rating)
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $tenantProfile = $user->tenantProfile;

        $rating = Rating::where('tenant_id', $tenantProfile->id)
            ->findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'communication_rating' => 'sometimes|integer|min:1|max:5',
            'accuracy_rating' => 'sometimes|integer|min:1|max:5',
            'cleanliness_rating' => 'sometimes|integer|min:1|max:5',
            'professionalism_rating' => 'sometimes|integer|min:1|max:5',
            'fairness_rating' => 'sometimes|integer|min:1|max:5',
            'review' => 'sometimes|nullable|string|max:500',
        ]);

        $rating->update($validated);

        // overall_rating is recalculated automatically via model event
        // landlord avg_rating is updated automatically via model event

        return response()->json([
            'success' => true,
            'message' => 'Rating updated successfully',
            'data' => $rating->fresh(['landlord.user', 'property']),
        ]);
    }

    /**
     * Delete a rating (tenant can delete their own rating)
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $tenantProfile = $user->tenantProfile;

        $rating = Rating::where('tenant_id', $tenantProfile->id)
            ->findOrFail($id);

        $landlordId = $rating->landlord_id;

        $rating->delete();

        // Update landlord's average rating after deletion
        $landlord = LandlordProfile::find($landlordId);
        if ($landlord) {
            $landlord->updateRating();
        }

        return response()->json([
            'success' => true,
            'message' => 'Rating deleted successfully',
        ]);
    }

    /**
     * Check if tenant can rate a landlord for a property
     */
    public function canRate(Request $request, $propertyId)
    {
        $user = $request->user();
        $tenantProfile = $user->tenantProfile;

        $property = Property::with('landlord')->findOrFail($propertyId);

        // Check if already rated
        $existingRating = Rating::where('landlord_id', $property->landlord_id)
            ->where('tenant_id', $tenantProfile->id)
            ->where('property_id', $property->id)
            ->first();

        return response()->json([
            'success' => true,
            'can_rate' => !$existingRating,
            'existing_rating' => $existingRating,
        ]);
    }
}