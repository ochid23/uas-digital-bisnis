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

        // Query transaksi user berbasis user_id, customer_email, maupun customer_name (Fallback multi-akun Google)
        $query = Transaction::query();

        $userName = trim($user->name);
        $userEmail = strtolower(trim($user->email));

        $query->where(function ($q) use ($user, $userEmail, $userName) {
            if (Schema::hasColumn('transactions', 'user_id')) {
                $q->where('user_id', $user->id);
            }
            $q->orWhere('customer_email', $user->email)
              ->orWhereRaw('LOWER(customer_email) = ?', [$userEmail]);

            if (!empty($userName) && strlen($userName) > 2) {
                $q->orWhereRaw('LOWER(customer_name) = ?', [strtolower($userName)]);
            }
        });

        $transactions = $query->with(['event', 'event.category'])
            ->latest()
            ->get();

        // Hubungkan user_id secara otomatis ke transaksi yang cocok jika belum terikat
        if (Schema::hasColumn('transactions', 'user_id')) {
            foreach ($transactions as $trx) {
                if (!$trx->user_id) {
                    $trx->update(['user_id' => $user->id]);
                }
            }
        }

        return view('ticket', compact('transactions'));
    }
}