<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver->stateless()->with(['prompt' => 'select_account'])->redirect();
    }

    public function callback()
    {
        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');
            $googleUser = $driver->stateless()->user();
            
            // Proses pencarian atau pembuatan user baru
            $user = \App\Models\User::firstOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => bcrypt('password_sso_default_123'), 
                    'role' => 'organizer', 
                ]
            );
            
            // Login dengan persistent remember-me (true) & regenerasi sesi
            Auth::login($user, true);
            request()->session()->regenerate();

            // Logika Redirect berdasarkan Role
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'organizer') {
                return redirect()->route('organizer.dashboard');
            }

            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Gagal login menggunakan Google: ' . $e->getMessage());
        }
    }
}