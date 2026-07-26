<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    /**
     * Tampilan Halaman Scanner QR Panitia Hari-H.
     */
    public function index()
    {
        return view('organizer.scanner');
    }

    /**
     * Proses Validasi & Check-in QR Code Tiket (Anti-Fraud / Anti Double Entry).
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $rawCode = trim($request->qr_code);

        // Ekstrak Order ID (Misal dari URL https://naazhi.my.id/success/TRX-xxx atau kode TRX-xxx saja)
        if (preg_match('/(TRX-[A-Za-z0-9\-]+)/', $rawCode, $matches)) {
            $orderId = $matches[1];
        } else {
            $orderId = $rawCode;
        }

        // Cari transaksi terkait
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'title' => 'TIKET TIDAK DITEMUKAN',
                'message' => "Kode Tiket [{$orderId}] tidak terdaftar di sistem.",
                'alert_type' => 'invalid',
            ], 404);
        }

        // 1. Cek Status Pembayaran (Harus Settlement / Success)
        if (!in_array(strtolower($transaction->status), ['settlement', 'success'])) {
            return response()->json([
                'status' => 'error',
                'title' => 'TIKET BELUM LUNAS',
                'message' => "Status pembayaran tiket ini masih '{$transaction->status}'.",
                'order_id' => $transaction->order_id,
                'customer_name' => $transaction->customer_name,
                'alert_type' => 'unpaid',
            ], 400);
        }

        // 2. CEK ANTI DOUBLE ENTRY (Pencegahan Penyusup / Penggunaan Ganda)
        if ($transaction->is_attended) {
            $attendedAt = $transaction->attended_at ? $transaction->attended_at->format('d M Y, H:i:s') : 'sebelumnya';
            return response()->json([
                'status' => 'warning',
                'title' => '⚠️ PERINGATAN: DOUBLE ENTRY!',
                'message' => "Tiket ini SUDAH DIGUNAKAN untuk check-in pada {$attendedAt}. Mencegah penyusupan!",
                'order_id' => $transaction->order_id,
                'customer_name' => $transaction->customer_name,
                'event_title' => $transaction->event->title ?? '-',
                'attended_at' => $attendedAt,
                'alert_type' => 'already_used',
            ], 422);
        }

        // 3. PROSES CHECK-IN BERHASIL (VALIDASI PERTAMA KALI)
        $transaction->is_attended = true;
        $transaction->attended_at = now();
        $transaction->generateCertificateCode();
        $transaction->save();

        return response()->json([
            'status' => 'success',
            'title' => '✅ CHECK-IN BERHASIL!',
            'message' => 'Peserta terverifikasi dan tiket resmi digunakan.',
            'order_id' => $transaction->order_id,
            'customer_name' => $transaction->customer_name,
            'customer_email' => $transaction->customer_email,
            'customer_phone' => $transaction->customer_phone,
            'event_title' => $transaction->event->title ?? '-',
            'attended_at' => $transaction->attended_at->format('d M Y, H:i:s'),
            'certificate_code' => $transaction->certificate_code,
            'alert_type' => 'success',
        ]);
    }
}
