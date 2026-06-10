<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // 
    }

    public function show(Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu (jika dibutuhkan)
        $categories = Category::all();
        
        // Me-render view dengan membawa data kategori dan data spesifik acara
        return view('event-detail', compact('categories', 'event'));
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function ticket()
    {
        return view('ticket');
    }
}