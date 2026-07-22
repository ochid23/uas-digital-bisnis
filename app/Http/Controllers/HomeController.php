<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Partner; // TUGAS 4: Panggil Model Partner

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua jenis kategori untuk tampilan filter tab button 
        $categories = Category::all();

        // 2. Buat kueri dasar untuk mengambil event
        $query = Event::with('category')
                      ->orderBy('date', 'asc');

        // 3. Filter query jika url memiliki parameter pencarian spesifik ?category=...
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Eksekusi query event
        $events = $query->get();

        // 5. TUGAS 4: Ambil sekumpulan data "Partner"
        $partners = Partner::latest()->get();

        // 6. TUGAS 4: Kirim data variabel $partners hasilnya ke template Blade
        return view('welcome', compact('events', 'categories', 'partners'));
    }
}