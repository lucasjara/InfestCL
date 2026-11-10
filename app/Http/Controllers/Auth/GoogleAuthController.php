<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile'])
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $email = $googleUser->getEmail();
        $googleId = $googleUser->getId();

        $emailVerified = filter_var(
            $googleUser->user['email_verified'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );

        if (!$email || !$googleId || !$emailVerified) {
            abort(403, 'No se pudo verificar la cuenta de Google.');
        }

        $user = User::where('google_id', $googleId)->first();

        if (!$user) {
            $user = User::where('email', $email)->first();

            if ($user) {
                if ($user->google_id !== null) {
                    abort(403, 'La cuenta ya está vinculada.');
                }

                $user->google_id = $googleId;
                $user->save();
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Usuario INFEST',
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => Hash::make(Str::random(40)),
                    'email_verified_at' => now(),
                ]);
            }
        }

        Auth::login($user, true);

        request()->session()->regenerate();

        return redirect()->intended(route('achievements.index'));
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}