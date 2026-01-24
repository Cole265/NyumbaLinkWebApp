<?php
// app/Http/Middleware/EnsureLandlordVerified.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLandlordVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'landlord') {
            return response()->json([
                'message' => 'Unauthorized. Landlord access required.'
            ], 403);
        }

        if (!$user->landlordProfile || !$user->landlordProfile->isVerified()) {
            return response()->json([
                'message' => 'Your landlord account is not verified. Please submit verification documents.',
                'verification_status' => $user->landlordProfile?->verification_status ?? 'pending',
            ], 403);
        }

        return $next($request);
    }
}