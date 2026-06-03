<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    // READ & SEARCH: Menampilkan data dan fitur pencarian (Soal 3)
    public function index(Request $request)
    {
        // 1. Buat query dasar
        $query = Partner::query();

        // 2. Jika ada input 'search' dari form, saring data menggunakan Eloquent LIKE
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 3. Eksekusi query dan ambil datanya (diurutkan dari yang terbaru)
        $partners = $query->latest()->get(); 
        
        return view('admin.partners.index', compact('partners'));
    }

    // CREATE: Menyimpan request data ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'logo_url' => 'required|string|url'
        ]);

        Partner::create([
            'name' => $request->name,
            'logo_url' => $request->logo_url
        ]);

        return redirect()->back()->with('success', 'Partner baru berhasil ditambahkan!');
    }

    // UPDATE: Menampilkan halaman edit partner
    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    // UPDATE: Menyimpan perubahan data partner
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'logo_url' => 'required|string|url'
        ]);

        $partner = Partner::findOrFail($id);
        $partner->update([
            'name' => $request->name,
            'logo_url' => $request->logo_url
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Data partner berhasil diperbarui!');
    }

    // DELETE: Menghapus data partner
    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}