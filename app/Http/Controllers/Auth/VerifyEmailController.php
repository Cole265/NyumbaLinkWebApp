<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Handle the verification link click (signed URL; no auth required).
     */
    public function verify(Request $request, string $id, string $hash)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Your email is already verified. You can log in.');
        }
        $expected = sha1($user->getEmailForVerification());
        if (!hash_equals($expected, $hash)) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }
        $user->markEmailAsVerified();
        return redirect()->route('login')->with('status', 'Your email has been verified. You can now log in.');
    }

    /**
     * Show the verification notice (requires auth via cookie).
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Resend the verification email (requires auth).
     */
    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }
}
