<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User; // Tambahan model User untuk menghitung pertumbuhan pengguna

class DashboardController extends Controller
{
    public function index()
    {
        // ========================================================
        // 1. STATISTIK KARTU ATAS & RIWAYAT TRANSAKSI (KODE LAMA)
        // ========================================================

        // Menjumlahkan semua nominal total_price dari kolom Transaksi Lunas
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])->sum('total_price');

        // Menghitung Berapa orang tamu yang tiketnya sudah Lunas
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])->count();

        // Menghitung Jumlah Acara Mendatang yang aktif diselenggarakan
        $activeEvents = Event::where('date', '>=', now())->count();

        // Menghitung Transaksi Ngadat (Status belum dibayar pelanggan / Expired)
        $pendingOrders = Transaction::where('status', 'pending')->count();

        // Menyertakan 5 daftar riwayat pesanan (History) paling mutakhir di panel
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();


        // ========================================================
        // 2. LOGIKA PERHITUNGAN UNTUK GRAFIK (KODE BARU)
        // ========================================================

        // Mengambil hanya kolom tanggal untuk efisiensi memori
        $users = User::select('created_at')->get();
        $events = Event::select('created_at')->get();

        // Siapkan wadah (array) kosong dengan nilai 0 untuk 12 bulan (indeks 1 = Jan, 12 = Des)
        $userCounts = array_fill(1, 12, 0);
        $eventCounts = array_fill(1, 12, 0);

        // Memasukkan dan menghitung jumlah user berdasarkan bulan daftarnya
        foreach ($users as $user) {
            if ($user->created_at) {
                $bulan = (int) $user->created_at->format('m');
                $userCounts[$bulan]++;
            }
        }

        // Memasukkan dan menghitung jumlah event berdasarkan bulan dibuatnya
        foreach ($events as $event) {
            if ($event->created_at) {
                $bulan = (int) $event->created_at->format('m');
                $eventCounts[$bulan]++;
            }
        }

        // Susun ulang indeks array menjadi 0-11 agar mudah dibaca oleh Chart.js
        $userChartData = array_values($userCounts);
        $eventChartData = array_values($eventCounts);

        // Label bulan baku untuk sumbu X pada grafik
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Kembalikan semua variabel ke halaman view (termasuk yang baru kita buat)
        return view('admin.dashboard', compact(
            'totalRevenue', 
            'ticketsSold', 
            'activeEvents', 
            'pendingOrders', 
            'recentTransactions',
            'userChartData',
            'eventChartData',
            'months'
        ));
    }
}