<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventTicketMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        if ($event->status && $event->status !== 'approved') {
            return redirect()->route('home')->with('error', 'Event ini belum disetujui oleh Admin dan belum terbuka untuk pemesanan.');
        }

        // Jalankan pembersihan reservasi expired secara otomatis
        Transaction::releaseExpired(15);

        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        return view('checkout.create', compact('event','categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // Auto release reservasi tiket expired sebelum reservasi baru
        Transaction::releaseExpired(15);

        // 2. Generate Kode TRX (Unik) & Total Harga
        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $totalPrice = ($event->price == 0) ? 0 : ($event->price + 5000);

        $transaction = null;
        $userId = Auth::id();

        // 3. PESSIMISTIC LOCKING & ATOMIC STOCK RESERVATION (BEST PRACTICE RACE CONDITION)
        // Menahan stok (-1) secara langsung saat tombol checkout diklik (Reserved Ticket)
        try {
            DB::transaction(function () use ($request, $event, $orderId, $totalPrice, $userId, &$transaction) {
                $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

                if (!$lockedEvent || $lockedEvent->stock <= 0) {
                    throw new \Exception('STOK_HABIS');
                }

                // Tahan (reserve) stok tiket secara otomatis (-1)
                $lockedEvent->decrement('stock', 1);

                // Rekam transaksi awal (status pending / success jika gratis)
                $transactionData = [
                    'event_id' => $event->id,
                    'order_id' => $orderId,
                    'customer_name' => trim($request->customer_name),
                    'customer_email' => trim($request->customer_email),
                    'customer_phone' => trim($request->customer_phone),
                    'total_price' => $totalPrice,
                    'status' => ($totalPrice == 0) ? 'success' : 'pending',
                ];

                if ($userId && \Illuminate\Support\Facades\Schema::hasColumn('transactions', 'user_id')) {
                    $transactionData['user_id'] = $userId;
                }

                $transaction = Transaction::create($transactionData);
            });
        } catch (\Exception $e) {
            if ($e->getMessage() === 'STOK_HABIS') {
                return back()->with('error', 'Mohon maaf, tiket untuk acara ini baru saja habis.');
            }
            return back()->with('error', 'Gagal memproses reservasi tiket: ' . $e->getMessage());
        }

        // ========================================================
        // 4. FITUR BYPASS ACARA GRATIS
        // ========================================================
        if ($totalPrice == 0) {
            try {
                Mail::to($transaction->customer_email)->send(new EventTicketMail($transaction));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email E-Ticket untuk tiket gratis: ' . $e->getMessage());
            }

            try {
                \App\Services\WhatsAppService::sendTicketNotification($transaction);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim WA E-Ticket untuk tiket gratis: ' . $e->getMessage());
            }

            return redirect()->route('checkout.success', $transaction->order_id)
                             ->with('success', 'Tiket gratis berhasil diklaim!');
        }
        // ========================================================


        // --- 5. INTEGRASI SNAP MIDTRANS (UNTUK TIKET BERBAYAR) ---

        // Konfigurasi Kredensial Environment Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Mode Sandbox
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        \Midtrans\Config::$curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [] 
        );

        // Susun Paket Array Data Transaksi (termasuk batas expired 15 menit)
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s O"),
                'unit' => 'minute',
                'duration' => 15
            ]
        ];

        try {
            // Perintah Tembak Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Update rekaman kita bahwa transaksi terkait sudah memiliki snap token
            $transaction->update(['snap_token' => $snapToken]);

            // Kirim notifikasi WA link pembayaran instan (pemulihan jika tab ditutup)
            try {
                \App\Services\WhatsAppService::sendAbandonedCartReminder($transaction);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim WA Pending Payment Link: ' . $e->getMessage());
            }

            // Redirect ke halaman antarmuka pembayaran final pelanggan
            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction','categories'));
    }

    public function success($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        // Hubungkan ke user jika belum ada user_id dan user sedang login
        if (Auth::check() && !$transaction->user_id && \Illuminate\Support\Facades\Schema::hasColumn('transactions', 'user_id')) {
            $transaction->update(['user_id' => Auth::id()]);
        }

        // KONDISI BYPASS PADA HALAMAN SUCCESS (Tiket gratis)
        if (strtolower($transaction->status) === 'success') {
             return view('checkout.success', compact('transaction', 'categories'));
        }

        // Konfigurasi Midtrans untuk mengecek status transaksi langsung ke API (Hanya untuk yang berbayar)
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        \Midtrans\Config::$curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => []
        );

        try {
            // Mengecek status pesanan secara mandiri (Bypass Webhook)
            $status = \Midtrans\Transaction::status($order_id);

            if ($status) {
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

                if (in_array($trx_status, ['settlement', 'capture'])) {
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => 'success']);

                        try {
                            Mail::to($transaction->customer_email)
                                ->send(new EventTicketMail($transaction));
                        } catch (\Exception $e) {
                            Log::error('Gagal mengirim email E-Ticket secara manual (Bypass): ' . $e->getMessage());
                        }

                        try {
                            \App\Services\WhatsAppService::sendTicketNotification($transaction);
                        } catch (\Exception $e) {
                            Log::error('Gagal mengirim WA E-Ticket secara manual (Bypass): ' . $e->getMessage());
                        }
                    }
                } else if (in_array($trx_status, ['expire', 'cancel', 'deny'])) {
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => ($trx_status === 'expire') ? 'expired' : 'failed']);
                        if ($transaction->event) {
                            $transaction->event->increment('stock', 1);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Log warning & lanjut tampilkan halaman sukses
            Log::warning('Midtrans status check warning: ' . $e->getMessage());
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }
}