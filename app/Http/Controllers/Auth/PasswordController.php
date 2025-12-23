<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Show the password change form.
     */
    public function showChangeForm()
    {
        return view('auth.password-change');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Notify User
        $request->user()->notify(new \App\Notifications\SystemAlert([
            'title' => 'Security Alert: Password Changed',
            'message' => 'Your account password was recently updated. If you did not perform this action, please contact support immediately.',
            'icon' => 'fa-key',
            'action_url' => route('profile.show')
        ]));

        return back()->with('status', 'password-updated');
    }
}
