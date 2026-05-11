<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.setting', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Isi data name dan email
        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Cek apakah user mengisi input password baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Pastikan route name sesuai, jika sebelumnya 'profile.setting' gunakan itu
        return Redirect::route('profile.setting')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Upload a new profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // max 2MB
        ]);

        $user = $request->user();
        // Store the file in the public disk
        $path = $request->file('photo')->store('profile-photos', 'public');
        // If user already has a photo, delete old one
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        $user->profile_photo_path = $path;
        $user->save();

        return Redirect::back()->with('status', 'profile-photo-updated');
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(Request $request)
    {
        $user = $request->user();
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }
        return Redirect::back()->with('status', 'profile-photo-deleted');
    }

}
