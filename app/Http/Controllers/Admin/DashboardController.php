<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Event; // Tambahan import model Event

class DashboardController extends Controller
{
    function index(){
        // 1. Total Pendapatan (Hanya menjumlahkan total_price dari transaksi yang sukses)
        $totalPendapatan = Transaction::whereIn('status', ['success', 'settlement'])->sum('total_price');

        // 2. Tiket Terjual (Jumlah transaksi yang sukses)
        $tiketTerjual = Transaction::whereIn('status', ['success', 'settlement'])->count();

        // 3. Event Aktif (Jumlah event yang tanggalnya hari ini atau di masa depan)
        $eventAktif = Event::where('date', '>=', now())->count();

        // 4. Pesanan Pending (Jumlah transaksi berstatus pending)
        $pesananPending = Transaction::where('status', 'pending')->count();

        // 5. Transaksi Terakhir (5 data terbaru)
        $latestTransactions = Transaction::with('event')->latest()->take(5)->get();

        // Mengirimkan semua variabel ke view
        return view('admin.dashboard', compact(
            'totalPendapatan', 
            'tiketTerjual', 
            'eventAktif', 
            'pesananPending', 
            'latestTransactions'
        ));
    }

    function indexEvent(){
        return view('admin.events');
    }

    function indexTransaction(){
        return view('admin.transactions');
    }
}