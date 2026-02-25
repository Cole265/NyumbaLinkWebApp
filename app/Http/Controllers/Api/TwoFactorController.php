<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function show(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'enabled' => !is_null($user->two_factor_confirmed_at),
        ]);
    }

    public function enable(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $recoveryCodes = collect(range(1, 8))->map(function () {
            return Str::random(10) . '-' . Str::random(10);
        })->toArray();

        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => null,
        ])->save();

        $qrUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return response()->json([
            'secret' => $secret,
            'recovery_codes' => $recoveryCodes,
            'qr_url' => $qrUrl,
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$user->two_factor_secret) {
            throw ValidationException::withMessages([
                'code' => ['Two-factor authentication is not initialized.'],
            ]);
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        $valid = $google2fa->verifyKey($secret, $request->input('code'));

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => ['The provided authentication code is invalid.'],
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        return response()->json([
            'message' => 'Two-factor authentication enabled successfully.',
        ]);
    }

    public function disable(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return response()->json([
            'message' => 'Two-factor authentication disabled.',
        ]);
    }

    public function verifyDuringLogin(User $user, string $code): bool
    {
        if (is_null($user->two_factor_confirmed_at)) {
            return true;
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        if ($google2fa->verifyKey($secret, $code)) {
            return true;
        }

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes ?? encrypt('[]')), true) ?: [];

        if (in_array($code, $recoveryCodes, true)) {
            $remaining = array_values(array_diff($recoveryCodes, [$code]));

            $user->forceFill([
                'two_factor_recovery_codes' => encrypt(json_encode($remaining)),
            ])->save();

            return true;
        }

        return false;
    }
}

