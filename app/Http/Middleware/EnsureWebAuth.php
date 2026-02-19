<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureWebAuth
{
    /**
     * Handle an incoming request. Redirect to login if no valid auth cookie.
     * Optionally require a role (landlord|tenant|admin) for role-specific routes.
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        $token = $request->cookie('auth_token');

        if (!$token) {
            return redirect()->route('login')->with('message', 'Please log in to continue.');
        }

        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken) {
            $this->clearAuthCookie();
            return redirect()->route('login')->with('message', 'Your session has expired. Please log in again.');
        }

        $user = $accessToken->tokenable;
        if (!$user) {
            $this->clearAuthCookie();
            return redirect()->route('login');
        }

        if ($user->is_suspended ?? false) {
            $this->clearAuthCookie();
            return redirect()->route('login')->with('error', 'Your account has been suspended. Please contact support.');
        }

        if ($role !== null && $user->role !== $role) {
            if ($user->role === 'landlord') {
                return redirect('/landlord/dashboard');
            }
            if ($user->role === 'tenant') {
                return redirect('/tenant/dashboard');
            }
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            return redirect()->route('login');
        }

        $request->setUserResolver(fn () => $user);
        return $next($request);
    }

    private function clearAuthCookie(): void
    {
        cookie()->queue(cookie()->forget('auth_token'));
    }
}
