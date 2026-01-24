<?php
// app/Http/Controllers/Api/Admin/DashboardController.php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Listing;
use App\Models\Transaction;
use App\Models\Inquiry;
use App\Models\Rating;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;
use App\Notifications\PropertyApproved;
use App\Notifications\PropertyRejected;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get admin dashboard overview
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Admin dashboard',
            'stats' => $this->getStats(),
        ]);
    }

    /**
     * Get platform statistics
     */
    public function stats()
    {
        $stats = [
            // User statistics
            'users' => [
                'total' => User::count(),
                'landlords' => User::where('role', 'landlord')->count(),
                'tenants' => User::where('role', 'tenant')->count(),
                'verified_landlords' => User::where('role', 'landlord')
                    ->whereHas('landlordProfile', function ($q) {
                        $q->where('verification_status', 'approved');
                    })->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ],

            // Property statistics
            'properties' => [
                'total' => Property::count(),
                'published' => Property::where('status', 'published')->count(),
                'pending_review' => Property::where('status', 'pending_review')->count(),
                'draft' => Property::where('status', 'draft')->count(),
                'rented' => Property::where('status', 'rented')->count(),
                'sold' => Property::where('status', 'sold')->count(),
                'by_type' => Property::select('property_type', DB::raw('count(*) as count'))
                    ->groupBy('property_type')
                    ->get(),
            ],

            // Financial statistics
            'revenue' => [
                'total' => Transaction::where('status', 'completed')->sum('amount'),
                'this_month' => Transaction::where('status', 'completed')
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount'),
                'registration_fees' => Transaction::where('transaction_type', 'registration_fee')
                    ->where('status', 'completed')
                    ->sum('amount'),
                'listing_fees' => Transaction::where('transaction_type', 'listing_fee')
                    ->where('status', 'completed')
                    ->sum('amount'),
                'boost_fees' => Transaction::where('transaction_type', 'boost_fee')
                    ->where('status', 'completed')
                    ->sum('amount'),
            ],

            // Activity statistics
            'activity' => [
                'total_listings' => Listing::count(),
                'active_listings' => Listing::where('is_active', true)
                    ->where('expiry_date', '>=', now())
                    ->count(),
                'total_inquiries' => Inquiry::count(),
                'pending_inquiries' => Inquiry::where('status', 'pending')->count(),
                'total_ratings' => Rating::count(),
                'avg_platform_rating' => round(Rating::avg('overall_rating'), 2),
            ],

            // Verification statistics
            'verifications' => [
                'pending' => VerificationRequest::where('status', 'pending')->count(),
                'approved' => VerificationRequest::where('status', 'approved')->count(),
                'rejected' => VerificationRequest::where('status', 'rejected')->count(),
            ],

            // City breakdown
            'cities' => Property::select('city', DB::raw('count(*) as count'))
                ->where('status', 'published')
                ->groupBy('city')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get pending properties for review
     */
    public function pendingProperties()
    {
        $properties = Property::with([
            'landlord.user',
            'images',
            'amenities',
        ])
        ->where('status', 'pending_review')
        ->orderBy('created_at', 'asc')
        ->paginate(20);

        // Add computed fields
        $properties->getCollection()->transform(function ($property) {
            $property->landlord_name = $property->landlord->user->name;
            $property->landlord_verified = $property->landlord->isVerified();
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
     * Approve property
     */
    public function approveProperty(Request $request, $id)
    {
        $property = Property::with('landlord.user')->findOrFail($id);

        if ($property->status !== 'pending_review') {
            return response()->json([
                'success' => false,
                'message' => 'This property is not pending review',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Update property status
            $property->update(['status' => 'published']);

            // Create active listing (30 days by default)
            Listing::create([
                'property_id' => $property->id,
                'start_date' => now(),
                'expiry_date' => now()->addDays(30),
                'is_active' => true,
                'view_count' => 0,
                'inquiry_count' => 0,
            ]);

            DB::commit();

            $property->landlord->user->notify(new PropertyApproved($property));

            // TODO: Send notification to landlord

            return response()->json([
                'success' => true,
                'message' => 'Property approved and published',
                'data' => $property->fresh(['listing']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve property',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject property
     */
    public function rejectProperty(Request $request, $id)
    {
        $property = Property::with('landlord.user')->findOrFail($id);

        if ($property->status !== 'pending_review') {
            return response()->json([
                'success' => false,
                'message' => 'This property is not pending review',
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $property->update([
            'status' => 'draft',
            // TODO: Store rejection reason in a separate field or notes table
        ]);

        $property->landlord->user->notify(new PropertyRejected(
            $property,
            $validated['reason']
        ));


        return response()->json([
            'success' => true,
            'message' => 'Property rejected and sent back to draft',
            'data' => $property->fresh(),
        ]);
    }

    /**
     * Get all users
     */
    public function users(Request $request)
    {
        $role = $request->get('role', 'all');
        $search = $request->get('search');

        $users = User::with(['landlordProfile', 'tenantProfile'])
            ->when($role !== 'all', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Add computed fields
        $users->getCollection()->transform(function ($user) {
            if ($user->role === 'landlord') {
                $user->verification_status = $user->landlordProfile->verification_status ?? 'pending';
                $user->total_properties = $user->landlordProfile->properties()->count() ?? 0;
                $user->avg_rating = $user->landlordProfile->avg_rating ?? 0;
            }
            
            return $user;
        });

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Suspend user
     */
    public function suspendUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot suspend admin users',
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // TODO: Add is_suspended field to users table and implement suspension logic
        // For now, just mark as unverified
        $user->update(['is_verified' => false]);

        return response()->json([
            'success' => true,
            'message' => 'User suspended successfully',
            'data' => $user->fresh(),
        ]);
    }

    /**
     * Private helper to get quick stats
     */
    private function getStats()
    {
        return [
            'total_users' => User::count(),
            'total_properties' => Property::count(),
            'pending_verifications' => VerificationRequest::where('status', 'pending')->count(),
            'pending_properties' => Property::where('status', 'pending_review')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
        ];
    }
}