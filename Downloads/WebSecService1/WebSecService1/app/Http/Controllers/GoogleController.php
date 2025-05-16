<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(rand(100000, 999999)),
                    'email_verified_at' => now(), // Google users are automatically verified
                ]);
                
                // Assign customer role by default
                $user->assignRole('customer');
            }
            
            Auth::login($user);
            
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }
    }
}
