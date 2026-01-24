<?php
// app/Http/Controllers/Api/LandlordController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\VerificationRequest;
use App\Models\Transaction;
use App\Models\Inquiry;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LandlordController extends Controller
{
    /**
     * Get landlord profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $landlord = $user->landlordProfile;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'landlord_profile' => $landlord,
                'stats' => [
                    'total_properties' => $landlord->properties()->count(),
                    'published_properties' => $landlord->properties()->where('status', 'published')->count(),
                    'pending_properties' => $landlord->properties()->where('status', 'pending_review')->count(),
                    'avg_rating' => $landlord->avg_rating,
                    'total_ratings' => $landlord->total_ratings,
                ],
            ],
        ]);
    }

    /**
     * Update landlord profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $landlord = $user->landlordProfile;

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
            'business_name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:500',
            'city' => 'sometimes|string|max:100',
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

            // Update landlord profile
            if (isset($validated['business_name']) || isset($validated['address']) || isset($validated['city'])) {
                $landlord->update([
                    'business_name' => $validated['business_name'] ?? $landlord->business_name,
                    'address' => $validated['address'] ?? $landlord->address,
                    'city' => $validated['city'] ?? $landlord->city,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user->fresh(),
                    'landlord_profile' => $landlord->fresh(),
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
     * Submit verification documents
     */
    public function submitVerification(Request $request)
    {
        $user = $request->user();
        $landlord = $user->landlordProfile;

        // Check if already verified
        if ($landlord->isVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is already verified',
            ], 422);
        }

        // Check if there's a pending verification
        $pendingVerification = VerificationRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingVerification) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending verification request',
                'verification' => $pendingVerification,
            ], 422);
        }

        $validated = $request->validate([
            'national_id' => 'required|string|max:50',
            'id_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
            'proof_of_ownership' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // Upload ID document
            $idDocPath = $request->file('id_document')->store('verifications/ids', 'public');

            // Upload proof of ownership if provided
            $proofPath = null;
            if ($request->hasFile('proof_of_ownership')) {
                $proofPath = $request->file('proof_of_ownership')->store('verifications/proofs', 'public');
            }

            // Update national ID in landlord profile
            $landlord->update([
                'national_id' => $validated['national_id'],
            ]);

            // Create verification request
            $verification = VerificationRequest::create([
                'user_id' => $user->id,
                'id_document_path' => $idDocPath,
                'proof_of_ownership' => $proofPath,
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Verification documents submitted successfully. Admin will review within 24-48 hours.',
                'data' => $verification,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded files on error
            if (isset($idDocPath)) {
                Storage::disk('public')->delete($idDocPath);
            }
            if (isset($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit verification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get verification status
     */
    public function verificationStatus(Request $request)
    {
        $user = $request->user();
        $landlord = $user->landlordProfile;

        $latestVerification = VerificationRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'is_verified' => $landlord->isVerified(),
                'verification_status' => $landlord->verification_status,
                'verified_at' => $landlord->verified_at,
                'latest_verification_request' => $latestVerification ? [
                    'id' => $latestVerification->id,
                    'status' => $latestVerification->status,
                    'submitted_at' => $latestVerification->created_at,
                    'reviewed_at' => $latestVerification->reviewed_at,
                    'admin_notes' => $latestVerification->admin_notes,
                ] : null,
            ],
        ]);
    }

    /**
     * Get landlord analytics dashboard
     */
    public function analytics(Request $request)
    {
        $user = $request->user();
        $landlord = $user->landlordProfile;

        // Time period filter (default: all time)
        $period = $request->get('period', 'all'); // all, month, week

        $dateFilter = match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            default => null,
        };

        // Property statistics
        $propertyStats = [
            'total_properties' => $landlord->properties()->count(),
            'published' => $landlord->properties()->where('status', 'published')->count(),
            'pending_review' => $landlord->properties()->where('status', 'pending_review')->count(),
            'draft' => $landlord->properties()->where('status', 'draft')->count(),
            'rented' => $landlord->properties()->where('status', 'rented')->count(),
            'sold' => $landlord->properties()->where('status', 'sold')->count(),
        ];

        // View statistics
        $viewStats = [
            'total_views' => DB::table('listings')
                ->join('properties', 'listings.property_id', '=', 'properties.id')
                ->where('properties.landlord_id', $landlord->id)
                ->sum('listings.view_count'),
            'views_this_month' => DB::table('listings')
                ->join('properties', 'listings.property_id', '=', 'properties.id')
                ->where('properties.landlord_id', $landlord->id)
                ->where('listings.created_at', '>=', now()->subMonth())
                ->sum('listings.view_count'),
        ];

        // Inquiry statistics
        $inquiryStats = [
            'total_inquiries' => Inquiry::whereHas('listing.property', function ($q) use ($landlord) {
                $q->where('landlord_id', $landlord->id);
            })->count(),
            'pending_inquiries' => Inquiry::whereHas('listing.property', function ($q) use ($landlord) {
                $q->where('landlord_id', $landlord->id);
            })->where('status', 'pending')->count(),
            'responded_inquiries' => Inquiry::whereHas('listing.property', function ($q) use ($landlord) {
                $q->where('landlord_id', $landlord->id);
            })->where('status', 'responded')->count(),
        ];

        // Revenue statistics
        $revenueStats = [
            'total_spent' => Transaction::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'registration_fees' => Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'registration_fee')
                ->where('status', 'completed')
                ->sum('amount'),
            'listing_fees' => Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'listing_fee')
                ->where('status', 'completed')
                ->sum('amount'),
            'boost_fees' => Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'boost_fee')
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        // Rating statistics
        $ratingStats = [
            'avg_rating' => $landlord->avg_rating,
            'total_ratings' => $landlord->total_ratings,
            'rating_breakdown' => [
                '5_stars' => Rating::where('landlord_id', $landlord->id)
                    ->where('overall_rating', '>=', 4.5)->count(),
                '4_stars' => Rating::where('landlord_id', $landlord->id)
                    ->whereBetween('overall_rating', [3.5, 4.49])->count(),
                '3_stars' => Rating::where('landlord_id', $landlord->id)
                    ->whereBetween('overall_rating', [2.5, 3.49])->count(),
                '2_stars' => Rating::where('landlord_id', $landlord->id)
                    ->whereBetween('overall_rating', [1.5, 2.49])->count(),
                '1_star' => Rating::where('landlord_id', $landlord->id)
                    ->where('overall_rating', '<', 1.5)->count(),
            ],
            'category_averages' => [
                'communication' => round(Rating::where('landlord_id', $landlord->id)->avg('communication_rating'), 2),
                'accuracy' => round(Rating::where('landlord_id', $landlord->id)->avg('accuracy_rating'), 2),
                'cleanliness' => round(Rating::where('landlord_id', $landlord->id)->avg('cleanliness_rating'), 2),
                'professionalism' => round(Rating::where('landlord_id', $landlord->id)->avg('professionalism_rating'), 2),
                'fairness' => round(Rating::where('landlord_id', $landlord->id)->avg('fairness_rating'), 2),
            ],
        ];

        // Top performing properties
        $topProperties = Property::where('landlord_id', $landlord->id)
            ->with(['listing', 'primaryImage'])
            ->withCount('inquiries')
            ->orderBy('inquiries_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'price' => $property->price,
                    'views' => $property->listing?->view_count ?? 0,
                    'inquiries' => $property->inquiries_count,
                    'image' => $property->primaryImage 
                        ? asset('storage/' . $property->primaryImage->image_path)
                        : null,
                ];
            });

        // Recent inquiries
        $recentInquiries = Inquiry::with(['tenant', 'listing.property'])
            ->whereHas('listing.property', function ($q) use ($landlord) {
                $q->where('landlord_id', $landlord->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($inquiry) {
                return [
                    'id' => $inquiry->id,
                    'property_title' => $inquiry->listing->property->title,
                    'tenant_name' => $inquiry->tenant->name,
                    'message' => $inquiry->message,
                    'status' => $inquiry->status,
                    'created_at' => $inquiry->created_at,
                ];
            });

        // Performance trends (last 6 months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            
            $monthlyTrends[] = [
                'month' => $month->format('M Y'),
                'views' => DB::table('listings')
                    ->join('properties', 'listings.property_id', '=', 'properties.id')
                    ->where('properties.landlord_id', $landlord->id)
                    ->whereYear('listings.created_at', $month->year)
                    ->whereMonth('listings.created_at', $month->month)
                    ->sum('listings.view_count'),
                'inquiries' => Inquiry::whereHas('listing.property', function ($q) use ($landlord) {
                    $q->where('landlord_id', $landlord->id);
                })
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'property_stats' => $propertyStats,
                'view_stats' => $viewStats,
                'inquiry_stats' => $inquiryStats,
                'revenue_stats' => $revenueStats,
                'rating_stats' => $ratingStats,
                'top_properties' => $topProperties,
                'recent_inquiries' => $recentInquiries,
                'monthly_trends' => $monthlyTrends,
            ],
        ]);
    }

    /**
     * Get property performance details
     */
    public function propertyPerformance(Request $request, $propertyId)
    {
        $landlord = $request->user()->landlordProfile;
        
        $property = Property::with(['listing', 'images', 'amenities'])
            ->where('landlord_id', $landlord->id)
            ->findOrFail($propertyId);

        $inquiries = Inquiry::whereHas('listing', function ($q) use ($property) {
            $q->where('property_id', $property->id);
        })->with('tenant')->get();

        $ratings = Rating::where('property_id', $property->id)
            ->with('tenant.user')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'property' => $property,
                'stats' => [
                    'total_views' => $property->listing?->view_count ?? 0,
                    'total_inquiries' => $inquiries->count(),
                    'pending_inquiries' => $inquiries->where('status', 'pending')->count(),
                    'total_ratings' => $ratings->count(),
                    'avg_rating' => $ratings->avg('overall_rating'),
                ],
                'inquiries' => $inquiries->map(function ($inquiry) {
                    return [
                        'id' => $inquiry->id,
                        'tenant_name' => $inquiry->tenant->name,
                        'message' => $inquiry->message,
                        'status' => $inquiry->status,
                        'created_at' => $inquiry->created_at,
                    ];
                }),
                'ratings' => $ratings->map(function ($rating) {
                    return [
                        'tenant_name' => $rating->tenant->user->name,
                        'overall_rating' => $rating->overall_rating,
                        'review' => $rating->review,
                        'created_at' => $rating->created_at,
                    ];
                }),
            ],
        ]);
    }
}