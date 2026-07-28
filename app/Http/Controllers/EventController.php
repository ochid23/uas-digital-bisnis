<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EventController extends Controller
{
    public function index()
    {
        // 
    }

    public function show(Event $event)
    {
        // Auto-release expired ticket reservations
        Transaction::releaseExpired(15);

        // Cek jika event belum disetujui (status != approved)
        if ($event->status && $event->status !== 'approved') {
            $user = auth()->user();
            if (!$user || ($user->role !== 'admin' && $user->id !== $event->organizer_id)) {
                abort(404, 'Event ini belum disetujui oleh Admin.');
            }
        }

        // Mengambil daftar kategori untuk keperluan menu
        $categories = Category::all();
        
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

        // Lepaskan otomatis transaksi pending yang sudah kadaluarsa (> 15 menit)
        Transaction::releaseExpired(15);

        // Query transaksi user berbasis user_id maupun customer_email
        $query = Transaction::query();

        if (Schema::hasColumn('transactions', 'user_id')) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email)
                  ->orWhereRaw('LOWER(customer_email) = ?', [strtolower(trim($user->email))]);
            });
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('customer_email', $user->email)
                  ->orWhereRaw('LOWER(customer_email) = ?', [strtolower(trim($user->email))]);
            });
        }

        $transactions = $query->with(['event', 'event.category'])
            ->latest()
            ->get();

        return view('ticket', compact('transactions'));
    }
}