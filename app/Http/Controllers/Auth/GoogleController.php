<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Rute untuk mengarahkan ke Google
    public function redirect()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    // Rute untuk menangani balasan dari Google
    public function callback()
    {
        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');
            
            // Ambil data user dari Google secara stateless
            $googleUser = $driver->stateless()->user();

            // Cek apakah user dengan email tersebut sudah ada di database
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Jika user sudah ada, update google_id jika kosong, tapi JANGAN ubah role-nya!
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName(), // Opsional: memperbarui nama jika berubah di Google
                ]);
                $user = $existingUser;
            } else {
                // Jika user benar-benar baru, buat akun baru dengan default role 'user'
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt('password_acak_123'),
                    'role' => 'user' 
                ]);
            }

            // Login-kan user tersebut ke sistem Laravel
            Auth::login($user);

            // ========================================================
            // TRAFFIC CONTROLLER: ATUR ARAH REDIRECT BERDASARKAN ROLE
            // ========================================================

            // 1. Jika akun terdeteksi sebagai Admin -> Lempar ke Dashboard Admin
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // 2. Jika akun terdeteksi sebagai Organizer -> Lempar ke Dashboard Organizer
            if ($user->role === 'organizer') {
                return redirect()->route('organizer.dashboard');
            }

            // 3. Jika User Biasa (Pembeli Tiket) -> Kembalikan ke halaman intended (Checkout Event tujuan)
            return redirect()->intended('/checkout');

        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan ke halaman login dengan pesan error
            return redirect('/login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}