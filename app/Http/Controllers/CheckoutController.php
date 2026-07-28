<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventTicketMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        if ($event->status && $event->status !== 'approved') {
            return redirect()->route('home')->with('error', 'Event ini belum disetujui oleh Admin dan belum terbuka untuk pemesanan.');
        }

        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

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

        // 2. Generate Kode TRX (Unik) & Total Harga
        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $totalPrice = ($event->price == 0) ? 0 : ($event->price + 5000);

        $transaction = null;

        // 3. PESIMISTIC LOCKING & ATOMIC STOCK RESERVATION
        // Menahan stok (-1) secara langsung saat tombol checkout diklik (mencegah Race Condition)
        try {
            DB::transaction(function () use ($request, $event, $orderId, $totalPrice, &$transaction) {
                $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

                if (!$lockedEvent || $lockedEvent->stock <= 0) {
                    throw new \Exception('STOK_HABIS');
                }

                // Tahan (reserve) stok tiket secara otomatis
                $lockedEvent->decrement('stock', 1);

                // Rekam transaksi awal (status pending)
                $transaction = Transaction::create([
                    'event_id' => $event->id,
                    'order_id' => $orderId,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'total_price' => $totalPrice,
                    'status' => ($totalPrice == 0) ? 'success' : 'pending',
                ]);
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


        // --- 6. INTEGRASI SNAP MIDTRANS (UNTUK TIKET BERBAYAR) ---

        // Konfigurasi Kredensial Environment Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Mode Sandbox!
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        \Midtrans\Config::$curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [] 
        );

        // Susun Paket Array Data Transaksi
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
        ];

        try {
            // Perintah Tembak Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Update rekaman kita bahwa transaksi terkait sudah memiliki id token pelunasan
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
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction','categories'));
    }

    public function success($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        // ------------------------------------------------------------
        // KONDISI BYPASS PADA HALAMAN SUCCESS:
        // Jika status transaksi sudah 'success' (artinya ini tiket gratis), 
        // jangan lakukan pengecekan ke API Midtrans karena transaksi ini 
        // tidak pernah didaftarkan ke Midtrans.
        // ------------------------------------------------------------
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
                // Mengambil nilai status transaksi
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

                // Jika API Midtrans mengonfirmasi bahwa transaksi telah berhasil (settlement / capture)
                if (in_array($trx_status, ['settlement', 'capture'])) {
                    // Hanya lakukan update jika status di database lokal masih 'pending' (indikasi Webhook tidak masuk)
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
                }
            }
        } catch (\Exception $e) {
            // Jika terjadi error dari API Midtrans (transaksi tidak valid), kembalikan ke beranda
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }
}