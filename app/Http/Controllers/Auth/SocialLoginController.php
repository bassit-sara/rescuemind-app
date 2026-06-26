<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $email = $socialUser->getEmail() ?? $socialUser->getId() . '@' . $provider . '.local';

            // Check if user already exists
            $user = User::where('email', $email)->orWhere(function($query) use ($provider, $socialUser) {
                $query->where('provider', $provider)
                      ->where('provider_id', $socialUser->getId());
            })->first();

            if ($user) {
                // If user exists, update their provider data
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                ]);
            } else {
                // Create a new user
                $user = User::create([
                    'name' => $socialUser->getName() ?? 'User',
                    'email' => $email,
                    'password' => null, // No password for social login
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                    'email_verified_at' => now(), // Assume social emails are verified
                ]);
                $user->assignRole('user');
            }

            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            \Log::error('Social Login Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('login')->with('error', 'Unable to login using ' . ucfirst($provider) . '. Please try again.');
        }
    }
}
