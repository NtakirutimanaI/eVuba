<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth page
     */
    public function redirect()
    {
        // Redirect to Google login with stateless to prevent InvalidStateException
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            // Get user info from Google (stateless)
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Create or get existing user
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt('password'), // default password
                    'role' => 'customer',
                ]
            );

            // Assign role if using Spatie roles package
            if (method_exists($user, 'assignRole') && !$user->hasAnyRole(['admin', 'manager', 'employee', 'customer'])) {
                $user->assignRole('customer');
            }

            // Log in the user
            Auth::login($user);

            // Redirect based on role
            $role = $user->role ?? 'customer';

            if (in_array($role, ['admin', 'manager', 'employee', 'customer'])) {
                return redirect()->route($role . '.dashboard');
            }

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            // Handle errors gracefully
            return redirect()->route('login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
