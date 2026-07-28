<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Fungsi menampilkan halaman view formulir login
    public function showLogin() {
        if (Auth::check()) {
            return redirect()->route('home');
        }
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

            // LOGIKA REDIRECT BERDASARKAN ROLE
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'organizer') {
                return redirect()->route('organizer.dashboard'); 
            }

            return redirect()->intended(route('home'));
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 3. Fungsi menampilkan halaman view formulir registrasi
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    // 4. Fungsi memproses registrasi akun baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem. Silakan login.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $user = User::create([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang di AmikomEventHub.');
    }

    // 5. Fungsi memroses Log Out (Keluar secara eksplisit)
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}