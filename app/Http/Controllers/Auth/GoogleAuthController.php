<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (empty($googleUser->email)) {
                return redirect()->route('login')
                    ->withErrors('No email returned from Google account.');
            }

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if (!$user) {
                $avatarPath = null;
                if ($googleUser->avatar) {
                    try {
                        $contents = file_get_contents($googleUser->avatar);
                        if ($contents) {
                            $name = 'profile-photos/' . Str::random(40) . '.jpg';
                            \Illuminate\Support\Facades\Storage::disk('public')->put($name, $contents);
                            $avatarPath = $name;
                        }
                    } catch (\Exception $e) {
                        // ignore
                    }
                }

                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'provider'  => 'google',
                    'avatar'    => $googleUser->avatar,
                    'profile_photo_path' => $avatarPath,
                    'password'  => bcrypt(Str::random(24)),
                    'role_name' => 'User',
                    'status'    => 'Active',
                    'join_date' => now(),
                ]);
                $user->markEmailAsVerified();
            } else {
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'provider'  => 'google',
                    ]);
                    
                    if (!$user->profile_photo_path && $googleUser->avatar) {
                        try {
                            $contents = file_get_contents($googleUser->avatar);
                            if ($contents) {
                                $name = 'profile-photos/' . Str::random(40) . '.jpg';
                                \Illuminate\Support\Facades\Storage::disk('public')->put($name, $contents);
                                $user->update(['profile_photo_path' => $name]);
                            }
                        } catch (\Exception $e) {
                            // ignore
                        }
                    }
                }
                
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }
            }

            $user->update([
                'last_login' => now(),
            ]);

            Auth::login($user);

            return redirect()->route('home');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login')
                ->withErrors('Authentication failed. Please try again.');
        }
    }
}