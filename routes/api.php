<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\LandlordController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\Admin\VerificationController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/login-test', [AuthController::class, 'login']);

Route::prefix('v1')->group(function () {
    
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    //public ratings
    Route::get('/landlords/{landlordId}/ratings', [RatingController::class, 'landlordRatings']);
    Route::get('/landlords/{landlordId}/rating-stats', [RatingController::class, 'landlordRatingStats']);
    
    // Public property search
    Route::get('/properties', [SearchController::class, 'index']);
    // More specific routes must come before the generic /properties/{id}
    Route::get('/properties/{id}/ratings', [RatingController::class, 'propertyRatings']);
    Route::post('/properties/{id}/view', [SearchController::class, 'incrementView']);
    Route::get('/properties/{id}', [SearchController::class, 'show']);
    
    // Search filters
    Route::get('/cities', [SearchController::class, 'getCities']);
    Route::get('/property-types', [SearchController::class, 'getPropertyTypes']);
});

// Protected routes (authentication required)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Auth user info
    Route::get('/user', function (Request $request) {
        return $request->user()->load(['landlordProfile', 'tenantProfile', 'adminProfile']);
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Report property (any authenticated user)
    Route::post('/report-property', [\App\Http\Controllers\Api\ReportController::class, 'store']);
    
    // ============ LANDLORD ROUTES ============
    Route::middleware(['role:landlord'])->prefix('landlord')->group(function () {
        
        // Profile
        Route::get('/profile', [LandlordController::class, 'profile']);
        Route::put('/profile', [LandlordController::class, 'updateProfile']);

        //Stats
        Route::get('/properties/{property}/performance', [LandlordController::class, 'propertyPerformance']);
        
        // Verification
        Route::post('/verification/submit', [LandlordController::class, 'submitVerification']);
        Route::get('/verification/status', [LandlordController::class, 'verificationStatus']);
        
        // Properties (only verified landlords)
        Route::middleware(['verified.landlord'])->group(function () {
            // Rented Properties route must come before apiResource to avoid route conflict
            Route::get('/properties/rented', [PropertyController::class, 'rentedProperties']);
            
            Route::apiResource('properties', PropertyController::class);
            Route::post('/properties/{property}/publish', [PropertyController::class, 'publish']);
            Route::post('/properties/{property}/boost', [PropertyController::class, 'boost']);
            Route::post('/properties/{property}/rent', [PropertyController::class, 'markAsRented']);
            Route::get('/properties/{property}/inquiries', [PropertyController::class, 'propertyInquiries']);
        });
        
        // Search tenants (for assigning to properties)
        Route::get('/tenants/search', [LandlordController::class, 'searchTenants']);
        
        // Listings
        Route::get('/listings', [ListingController::class, 'landlordListings']);
        Route::get('/inquiries', [InquiryController::class, 'landlordInquiries']);
        Route::post('/inquiries/{inquiry}/respond', [InquiryController::class, 'respond']);
        Route::post('/inquiries/{inquiry}/close', [InquiryController::class, 'close']);
        Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy']);
        Route::get('/ratings', [RatingController::class, 'myLandlordRatings']);
        
        // Analytics
        Route::get('/analytics', [LandlordController::class, 'analytics']);
    });

    // ============NOTIFICATION ROUTES ============
    Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
});
    
    // ============ TENANT ROUTES ============
    Route::middleware(['role:tenant'])->prefix('tenant')->group(function () {
        
        // Profile
        Route::get('/profile', [InquiryController::class, 'tenantProfile']);
        Route::put('/profile', [InquiryController::class, 'updateTenantProfile']);
        
        // Inquiries
        Route::post('/inquiries', [InquiryController::class, 'store']);
        Route::get('/inquiries', [InquiryController::class, 'tenantInquiries']);
        
        // Tenancies
        Route::get('/tenancies', [TenantController::class, 'myTenancies']);
        Route::post('/tenancies/{tenancy}/vacate', [TenantController::class, 'vacateAndRate']);
        
        // Ratings
        Route::post('/ratings', [RatingController::class, 'store']);
        Route::get('/ratings', [RatingController::class, 'tenantRatings']);
        Route::put('/ratings/{rating}', [RatingController::class, 'update']);
        Route::delete('/ratings/{rating}', [RatingController::class, 'destroy']);

        // Favorites / saved properties
        Route::get('/favorites', [\App\Http\Controllers\Api\FavoriteController::class, 'index']);
        Route::get('/favorites/ids', [\App\Http\Controllers\Api\FavoriteController::class, 'ids']);
        Route::post('/favorites', [\App\Http\Controllers\Api\FavoriteController::class, 'store']);
        Route::delete('/favorites/{propertyId}', [\App\Http\Controllers\Api\FavoriteController::class, 'destroy']);
    });
    
    // ============ ADMIN ROUTES ============
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/stats', [DashboardController::class, 'stats']);
        Route::get('/contact-messages', [DashboardController::class, 'contactMessages']);
        
        // Verification Management
        Route::get('/verifications', [VerificationController::class, 'index']);
        Route::get('/verifications/{verification}', [VerificationController::class, 'show']);
        Route::post('/verifications/{verification}/approve', [VerificationController::class, 'approve']);
        Route::post('/verifications/{verification}/reject', [VerificationController::class, 'reject']);
        
        // Property moderation
        Route::get('/properties/pending', [DashboardController::class, 'pendingProperties']);
        Route::get('/properties/{property}', [DashboardController::class, 'showProperty']);
        Route::post('/properties/{property}/approve', [DashboardController::class, 'approveProperty']);
        Route::post('/properties/{property}/reject', [DashboardController::class, 'rejectProperty']);
        
        // User management
        Route::get('/users', [DashboardController::class, 'users']);
        Route::post('/users/{user}/suspend', [DashboardController::class, 'suspendUser']);

        // Property reports
        Route::get('/reports', [DashboardController::class, 'reports']);
        Route::post('/reports/{report}/review', [DashboardController::class, 'reportReview']);
        Route::post('/reports/{report}/dismiss', [DashboardController::class, 'reportDismiss']);
    });
});