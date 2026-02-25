<?php
// app/Http/Controllers/Api/SearchController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Get all published properties with advanced search & filters
     * 
     * Query parameters:
     * - city: Filter by city
     * - property_type: Filter by type (residential, land, commercial)
     * - min_price: Minimum price
     * - max_price: Maximum price
     * - bedrooms: Number of bedrooms
     * - bathrooms: Number of bathrooms
     * - is_furnished: true/false
     * - search: Search in title and description
     * - sort: Sort by (price_asc, price_desc, newest, popular)
     * - per_page: Results per page (default: 20)
     */
    public function index(Request $request)
    {
        $query = Property::query()
            ->with([
                'landlord.user',
                'primaryImage',
                'images',
                'amenities',
                'listing',
                'activeBoost'
            ])
            ->where('status', 'published')
            ->whereDoesntHave('activeTenancy') // Exclude rented properties
            ->whereHas('listing', function ($q) {
                $q->where('is_active', true)
                  ->where('expiry_date', '>=', now());
            });

        // City filter
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Property type filter
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Bedrooms filter
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // Bathrooms filter
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }

        // Furnished filter
        if ($request->filled('is_furnished')) {
            $query->where('is_furnished', $request->boolean('is_furnished'));
        }

        // District filter
        if ($request->filled('district')) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }

        // Area filter
        if ($request->filled('area')) {
            $query->where('area', 'like', '%' . $request->area . '%');
        }

        // Search in title and description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->withCount('inquiries')
                      ->orderBy('inquiries_count', 'desc');
                break;
            case 'newest':
            default:
                // Boosted properties first, then by creation date
                $query->orderByRaw('
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM property_boosts 
                            WHERE property_boosts.property_id = properties.id 
                            AND property_boosts.is_active = 1 
                            AND property_boosts.end_date >= ?
                        ) THEN 0 
                        ELSE 1 
                    END
                ', [now()])
                ->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 20);
        $properties = $query->paginate($perPage);

        // Add additional computed fields
        $properties->getCollection()->transform(function ($property) {
            $property->is_boosted = $property->isBoosted();
            $property->landlord_rating = $property->landlord->avg_rating;
            $property->landlord_name = $property->landlord->user->name;
            $property->primary_image_url = $property->primaryImage 
                ? asset('storage/' . $property->primaryImage->image_path)
                : null;
            
            return $property;
        });

        return response()->json([
            'success' => true,
            'data' => $properties,
            'filters_applied' => [
                'city' => $request->city,
                'property_type' => $request->property_type,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'bedrooms' => $request->bedrooms,
                'search' => $request->search,
                'sort' => $sort,
            ],
        ]);
    }

    /**
     * Get single property details
     */
    public function show($id)
    {
        $property = Property::with([
            'landlord.user',
            'landlord.ratings',
            'images',
            'amenities',
            'listing',
            'activeBoost',
            'ratings' => function ($query) {
                $query->latest()->limit(5);
            }
        ])
        ->where('status', 'published')
        ->findOrFail($id);

        // Calculate additional stats
        $property->is_boosted = $property->isBoosted();
        $property->landlord_rating = $property->landlord->avg_rating;
        $property->landlord_total_ratings = $property->landlord->total_ratings;
        $property->landlord_name = $property->landlord->user->name;
        $property->landlord_phone = $property->landlord->user->phone;
        $property->landlord_verified = $property->landlord->isVerified();
        $property->total_views = $property->listing?->view_count ?? 0;
        $property->total_inquiries = $property->listing?->inquiry_count ?? 0;

        // Format images
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
     * Get similar properties (same city and/or type, excluding current, limit 4)
     */
    public function similar($id)
    {
        $current = Property::where('status', 'published')->find($id);
        if (!$current) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $query = Property::query()
            ->with(['landlord.user', 'primaryImage', 'listing'])
            ->where('status', 'published')
            ->where('id', '!=', $id)
            ->whereDoesntHave('activeTenancy')
            ->whereHas('listing', function ($q) {
                $q->where('is_active', true)->where('expiry_date', '>=', now());
            });

        // Prefer same city, then same property_type
        $query->where(function ($q) use ($current) {
            $q->where('city', $current->city)
              ->orWhere('property_type', $current->property_type);
        });

        $properties = $query->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $properties->transform(function ($property) {
            $property->primary_image_url = $property->primaryImage
                ? asset('storage/' . $property->primaryImage->image_path)
                : null;
            $property->landlord_name = $property->landlord->user->name ?? null;
            return $property;
        });

        return response()->json([
            'success' => true,
            'data' => $properties,
        ]);
    }

    /**
     * Increment property view count
     */
    public function incrementView($id)
    {
        $property = Property::findOrFail($id);
        
        if ($property->listing) {
            $property->listing->increment('view_count');
        }

        return response()->json([
            'success' => true,
            'message' => 'View count updated',
            'view_count' => $property->listing?->view_count ?? 0,
        ]);
    }

    /**
     * Get all available cities (for filter dropdown)
     */
    public function getCities()
    {
        $cities = Property::where('status', 'published')
            ->select('city', DB::raw('count(*) as count'))
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cities,
        ]);
    }

    /**
     * Get property types with counts (for filter dropdown)
     */
    public function getPropertyTypes()
    {
        $types = Property::where('status', 'published')
            ->select('property_type', DB::raw('count(*) as count'))
            ->groupBy('property_type')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->property_type,
                    'label' => ucfirst(str_replace('_', ' ', $item->property_type)),
                    'count' => $item->count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $types,
        ]);
    }

    /**
     * Get price range statistics
     */
    public function getPriceStats()
    {
        $stats = Property::where('status', 'published')
            ->selectRaw('
                MIN(price) as min_price,
                MAX(price) as max_price,
                AVG(price) as avg_price,
                property_type
            ')
            ->groupBy('property_type')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get featured/boosted properties
     */
    public function getFeatured()
    {
        $properties = Property::with([
            'landlord.user',
            'primaryImage',
            'images',
            'amenities',
            'activeBoost'
        ])
        ->where('status', 'published')
        ->whereHas('activeBoost', function ($q) {
            $q->where('is_active', true)
              ->where('end_date', '>=', now());
        })
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

        $properties->transform(function ($property) {
            $property->is_boosted = true;
            $property->landlord_rating = $property->landlord->avg_rating;
            $property->landlord_name = $property->landlord->user->name;
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
     * Get similar properties (based on location and type)
     */
    public function getSimilar($id)
    {
        $property = Property::findOrFail($id);

        $similar = Property::with([
            'landlord.user',
            'primaryImage',
            'images',
        ])
        ->where('status', 'published')
        ->where('id', '!=', $property->id)
        ->where('property_type', $property->property_type)
        ->where(function ($query) use ($property) {
            $query->where('city', $property->city)
                  ->orWhere('district', $property->district);
        })
        ->whereBetween('price', [
            $property->price * 0.7,  // 30% lower
            $property->price * 1.3,  // 30% higher
        ])
        ->limit(6)
        ->get();

        $similar->transform(function ($prop) {
            $prop->primary_image_url = $prop->primaryImage 
                ? asset('storage/' . $prop->primaryImage->image_path)
                : null;
            return $prop;
        });

        return response()->json([
            'success' => true,
            'data' => $similar,
        ]);
    }
}