<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        // Mengambil semua data user, bisa juga menggunakan paginate() jika datanya banyak
        $users = User::all(); 
        
        return view('admin.users.index', compact('users'));
    }

    // Memproses perubahan role
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,organizer,user' // Sesuaikan dengan opsi role Anda
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui!');
    }
}