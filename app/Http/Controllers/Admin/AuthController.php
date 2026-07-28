<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Fungsi menampilkan halaman view formulir
    public function showLogin() {
        return view('auth.login');
    }

    // 2. Fungsi memproses validasi Submit Log In
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember', true);

        // Coba melakukan login dengan remember = true (persistent login)
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // ==========================================
            // LOGIKA REDIRECT BERDASARKAN ROLE
            // ==========================================
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'organizer') {
                return redirect()->route('organizer.dashboard'); 
            }

            return redirect()->route('home');
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 3. Fungsi memroses Log Out (Keluar secara eksplisit)
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}