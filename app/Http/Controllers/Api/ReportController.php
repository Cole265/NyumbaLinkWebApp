<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Report / flag a property (any authenticated user).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'reason' => 'required|string|min:10|max:2000',
        ]);

        $property = Property::findOrFail($validated['property_id']);
        $user = $request->user();

        // Optional: one report per user per property (allow resubmit with new reason)
        $report = Report::updateOrCreate(
            [
                'user_id' => $user->id,
                'property_id' => $property->id,
            ],
            [
                'reason' => $validated['reason'],
                'status' => 'pending',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Report submitted. Our team will review it.',
            'data' => $report,
        ], 201);
    }
}
