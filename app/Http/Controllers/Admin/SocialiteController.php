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

        // Tambahkan ->with(['prompt' => 'select_account']) sebelum ->redirect()
        return $driver->stateless()->with(['prompt' => 'select_account'])->redirect();
    }

    public function callback()
    {
        try {
            // 1. WAJIB TAMBAHKAN ->stateless() DI SINI AGAR SINKRON DENGAN REDIRECT
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');
            $googleUser = $driver->stateless()->user();
            
            // 2. Proses pencarian atau pembuatan user baru
            $user = \App\Models\User::firstOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => bcrypt('password_sso_default_123'), 
                    // Pastikan memberikan role default jika sistem Anda mewajibkannya.
                    // Jika akun Nayottama ini ditujukan sebagai organizer:
                    'role' => 'organizer', 
                ]
            );
            
            // Melakukan login
            Auth::login($user);

            // Logika Redirect berdasarkan Role
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'organizer') {
                return redirect()->route('organizer.dashboard');
            }

            return redirect()->route('home');

        } catch (\Exception $e) {
            // TIPS DEBUGGING:
            // Jika masih error, hapus tanda // pada baris dd() di bawah ini 
            // untuk melihat pesan error aslinya secara langsung di layar:
            
            // dd($e->getMessage()); 
            
            return redirect()->route('admin.login')->with('error', 'Gagal login menggunakan Google: ' . $e->getMessage());
        }
    }
}