<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        if ($request->previous_url) {
            session(['previous_url' => $request->previous_url]);
        }

        return Socialite::driver('google')->redirect();
    }

public function handleGoogleCallback()
{
    try {
        // Use stateless() to prevent session mismatch errors in production
        $user = Socialite::driver('google')->stateless()->user();

        // 1. Check if user already exists by Google ID
        $findUser = User::where('google_id', $user->id)->first();
        
        if ($findUser) {
            Auth::login($findUser);
        } else {
            // 2. Check if user exists by email but isn't linked to Google yet
            $findUserEmail = User::where('email', $user->email)->first();
            
            if ($findUserEmail) {
                $findUserEmail->update(['google_id' => $user->id]);
                $currentUser = $findUserEmail; 
            } else {
                // 3. Create a brand new user
                $currentUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt(Str::random(16)),
                    'username' => uniqid(),
                    'is_google_registered' => true
                ]);
            }
            Auth::login($currentUser); 
        }

        // 4. Suspension Check
        if (auth()->user()->is_suspended == 1) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is temporarily suspended');
        }

        // 5. Save device & Redirect
        // Resolving AuthController via the Service Container to handle dependencies
        $authController = app(\App\Http\Controllers\AuthController::class);
        $authController->saveTrustedDevice();

        if (session('previous_url')) {
            $url = session('previous_url');
            session()->forget('previous_url');
            return redirect($url);
        }

        return redirect()->route('backend.admin.dashboard');

    } catch (Exception $e) {
        // Log the error for debugging
        \Log::error('Google Auth Error: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Authentication failed, please try again.');
    }
}
}