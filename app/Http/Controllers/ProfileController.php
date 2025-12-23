<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Notify User
        $user->notify(new \App\Notifications\SystemAlert([
            'title' => 'Profile Updated',
            'message' => 'Your account details have been successfully updated.',
            'icon' => 'fa-user-check',
            'action_url' => route('profile.show')
        ]));

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Upload and save profile photo only
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile-photos', $filename, 'public');

            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists('profile-photos/' . $user->photo)) {
                Storage::disk('public')->delete('profile-photos/' . $user->photo);
            }

            $user->photo = $filename;
            $user->save();

            // Notify User
            $user->notify(new \App\Notifications\SystemAlert([
                'title' => 'Photo Updated',
                'message' => 'Your profile picture has been changed.',
                'icon' => 'fa-camera',
                'action_url' => route('profile.show')
            ]));
        }

        return Redirect::route('profile.show')->with('status', 'photo-updated');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $user = $request->user();
        $user->theme = $request->theme;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->photo && Storage::disk('public')->exists('profile-photos/' . $user->photo)) {
            Storage::disk('public')->delete('profile-photos/' . $user->photo);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
