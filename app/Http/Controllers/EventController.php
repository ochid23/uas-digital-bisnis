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
        // Cek jika event belum disetujui (status != approved)
        if ($event->status && $event->status !== 'approved') {
            $user = auth()->user();
            if (!$user || ($user->role !== 'admin' && $user->id !== $event->organizer_id)) {
                abort(404, 'Event ini belum disetujui oleh Admin.');
            }
        }

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
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('google.login');
        }

        $transactions = \App\Models\Transaction::where('customer_email', $user->email)
            ->orWhereRaw('LOWER(customer_email) = ?', [strtolower($user->email)])
            ->with('event')
            ->latest()
            ->get();

        return view('ticket', compact('transactions'));
    }
}