<?php
// app/Http/Controllers/Api/AuthController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LandlordProfile;
use App\Models\TenantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:landlord,tenant',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $user->sendEmailVerificationNotification();

        // Create role-specific profile
        if ($user->role === 'landlord') {
            LandlordProfile::create([
                'user_id' => $user->id,
                'verification_status' => 'pending',
            ]);
        } else {
            TenantProfile::create([
                'user_id' => $user->id,
            ]);
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = response()->json([
            'message' => 'Registration successful',
            'user' => $user->load(['landlordProfile', 'tenantProfile']),
            'token' => $token,
        ], 201);
        $response->cookie('auth_token', $token, 60 * 24 * 7, '/', null, request()->secure(), true, false, 'lax');
        return $response;
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->is_suspended ?? false) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been suspended. Please contact support.'],
            ]);
        }

        // Delete old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = response()->json([
            'message' => 'Login successful',
            'user' => $user->load(['landlordProfile', 'tenantProfile', 'adminProfile']),
            'token' => $token,
        ]);
        $response->cookie('auth_token', $token, 60 * 24 * 7, '/', null, request()->secure(), true, false, 'lax');
        return $response;
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $response = response()->json([
            'message' => 'Logged out successfully',
        ]);
        $response->withCookie(cookie()->forget('auth_token'));
        return $response;
    }
}
