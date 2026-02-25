<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AdminAccountController extends Controller
{
    /**
     * Get current admin's profile (user + adminProfile).
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load('adminProfile');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'admin_profile' => $user->adminProfile,
            ],
        ]);
    }

    /**
     * Update current admin's personal information (name, email, phone).
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
        ]);

        $user->update(array_filter($validated));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user->fresh('adminProfile'),
        ]);
    }

    /**
     * Change current admin's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Admin updates another user's personal information.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
        ]);

        $user->update(array_filter($validated));

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $user->fresh(['landlordProfile', 'tenantProfile', 'adminProfile']),
        ]);
    }

    /**
     * Admin sets a new password for another user (reset password).
     */
    public function resetUserPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'success' => true,
            'message' => 'User password has been reset.',
        ]);
    }
}
