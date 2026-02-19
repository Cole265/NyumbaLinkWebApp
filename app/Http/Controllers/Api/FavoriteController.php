<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * List tenant's saved/favorite properties
     */
    public function index(Request $request)
    {
        $favorites = Favorite::with(['property.primaryImage', 'property.listing'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $properties = $favorites->map(function ($fav) {
            $p = $fav->property;
            if (!$p) {
                return null;
            }
            $p->primary_image_url = $p->primaryImage
                ? asset('storage/' . $p->primaryImage->image_path)
                : null;
            $p->is_favorited = true;
            return $p;
        })->filter()->values();

        return response()->json([
            'success' => true,
            'data' => $properties,
        ]);
    }

    /**
     * Get only favorite property IDs (for UI state)
     */
    public function ids(Request $request)
    {
        $ids = Favorite::where('user_id', $request->user()->id)->pluck('property_id');
        return response()->json([
            'success' => true,
            'data' => $ids,
        ]);
    }

    /**
     * Add property to favorites
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $property = Property::findOrFail($validated['property_id']);
        if ($property->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Only published properties can be saved.',
            ], 422);
        }

        $fav = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'property_id' => $validated['property_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Property saved to favorites.',
            'data' => $fav->load('property'),
        ], 201);
    }

    /**
     * Remove property from favorites
     */
    public function destroy(Request $request, $propertyId)
    {
        $deleted = Favorite::where('user_id', $request->user()->id)
            ->where('property_id', $propertyId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Favorite not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Removed from favorites.',
        ]);
    }
}
