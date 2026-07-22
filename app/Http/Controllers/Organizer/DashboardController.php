<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $organizerId = Auth::id();

        // 1. Menghitung Total Event Aktif
        $activeEvents = Event::where('organizer_id', $organizerId)
                             ->where('date', '>=', now())
                             ->count();

        // 2. Menghitung Tiket Terjual
        $ticketsSold = Transaction::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizer_id', $organizerId);
        })->whereIn('status', ['settlement', 'success'])->count();

        // 3. Estimasi Pendapatan
        $totalRevenue = Transaction::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizer_id', $organizerId);
        })->whereIn('status', ['settlement', 'success'])->sum('total_price');

        // 4. Mengambil 5 Event Terbaru milik Organizer ini
        $recentEvents = Event::where('organizer_id', $organizerId)
                             ->latest()
                             ->take(5)
                             ->get();

        // 5. Mengambil Transaksi Peserta untuk Event milik Organizer ini
        $recentTransactions = Transaction::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizer_id', $organizerId);
        })->with('event')->latest()->take(10)->get();

        return view('organizer.dashboard', compact('activeEvents', 'ticketsSold', 'totalRevenue', 'recentEvents', 'recentTransactions'));
    }
}