<?php
// app/Http/Controllers/Api/Admin/VerificationController.php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationRequest;
use App\Models\LandlordProfile;
use Illuminate\Http\Request;
use App\Notifications\VerificationApproved;
use App\Notifications\VerificationRejected;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    /**
     * Get all verification requests
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $verifications = VerificationRequest::with(['user.landlordProfile'])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('status', 'asc') // Pending first
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Add computed fields
        $verifications->getCollection()->transform(function ($verification) {
            $verification->landlord_name = $verification->user->name;
            $verification->landlord_email = $verification->user->email;
            $verification->landlord_phone = $verification->user->phone;
            $verification->business_name = $verification->user->landlordProfile->business_name ?? null;
            $verification->id_document_url = $verification->id_document_path 
                ? asset('storage/' . $verification->id_document_path)
                : null;
            $verification->proof_url = $verification->proof_of_ownership 
                ? asset('storage/' . $verification->proof_of_ownership)
                : null;
            
            return $verification;
        });

        return response()->json([
            'success' => true,
            'data' => $verifications,
            'stats' => [
                'pending' => VerificationRequest::where('status', 'pending')->count(),
                'approved' => VerificationRequest::where('status', 'approved')->count(),
                'rejected' => VerificationRequest::where('status', 'rejected')->count(),
            ],
        ]);
    }

    /**
     * Get single verification details
     */
    public function show($id)
    {
        $verification = VerificationRequest::with(['user.landlordProfile'])
            ->findOrFail($id);

        $verification->landlord_name = $verification->user->name;
        $verification->landlord_email = $verification->user->email;
        $verification->landlord_phone = $verification->user->phone;
        $verification->business_name = $verification->user->landlordProfile->business_name ?? null;
        $verification->landlord_address = $verification->user->landlordProfile->address ?? null;
        $verification->id_document_url = $verification->id_document_path 
            ? asset('storage/' . $verification->id_document_path)
            : null;
        $verification->proof_url = $verification->proof_of_ownership 
            ? asset('storage/' . $verification->proof_of_ownership)
            : null;

        return response()->json([
            'success' => true,
            'data' => $verification,
        ]);
    }

    /**
     * Approve verification
     */
    public function approve(Request $request, $id)
    {
        $verification = VerificationRequest::with('user.landlordProfile')
            ->findOrFail($id);

        if ($verification->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This verification has already been processed',
            ], 422);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $verification->approve($validated['admin_notes'] ?? 'Verification approved');

            DB::commit();

            $verification->user->notify(new VerificationApproved(
                $verification->user->name,
                $validated['admin_notes'] ?? null
            )); 

            // TODO: Send notification to landlord (email/SMS)

            return response()->json([
                'success' => true,
                'message' => 'Landlord verification approved successfully',
                'data' => $verification->fresh(['user.landlordProfile']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve verification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject verification
     */
    public function reject(Request $request, $id)
    {
        $verification = VerificationRequest::with('user.landlordProfile')
            ->findOrFail($id);

        if ($verification->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This verification has already been processed',
            ], 422);
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $verification->reject($validated['admin_notes']);

            DB::commit();

            $verification->user->notify(new VerificationRejected(
                $verification->user->name,
                $validated['admin_notes']
            ));

            // TODO: Send notification to landlord with rejection reason

            return response()->json([
                'success' => true,
                'message' => 'Landlord verification rejected',
                'data' => $verification->fresh(['user.landlordProfile']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject verification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}