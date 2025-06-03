<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'first_name' => explode(' ', $googleUser->name)[0],
                    'last_name' => explode(' ', $googleUser->name)[1] ?? '',
                    'password' => bcrypt(Str::random(24)),
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'player',
                ]
            );

            Auth::login($user);

            return redirect()->route('player.dashboard')
                ->with('success', 'Successfully logged in with Google!');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while logging account',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
