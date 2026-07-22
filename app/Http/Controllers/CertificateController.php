<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Mail\EventCertificateMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    /**
     * Menampilkan desain E-Sertifikat Kehadiran berdasarkan kode sertifikat atau order_id.
     */
    public function show($code)
    {
        $transaction = Transaction::with(['event', 'event.category'])
            ->where('certificate_code', $code)
            ->orWhere('order_id', $code)
            ->firstOrFail();

        // Pastikan transaksi valid/sukses
        if (!in_array($transaction->status, ['settlement', 'success'])) {
            return redirect()->route('home')->with('error', 'Status transaksi belum selesai.');
        }

        // Jika belum memiliki kode sertifikat, otomatis generate
        if (!$transaction->certificate_code) {
            $transaction->generateCertificateCode();
        }

        return view('certificates.show', compact('transaction'));
    }

    /**
     * Menerbitkan E-Sertifikat Kehadiran & mengirimkan langsung ke Email Peserta.
     */
    public function issue($order_id)
    {
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        // 1. Tandai kehadiran peserta & buat kode sertifikat
        $code = $transaction->generateCertificateCode();
        $transaction->update([
            'is_attended' => true,
            'attended_at' => $transaction->attended_at ?? now(),
            'certificate_sent_at' => now(),
        ]);

        // 2. Kirim Email E-Sertifikat ke Email Peserta
        try {
            Mail::to($transaction->customer_email)->send(new EventCertificateMail($transaction));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email E-Sertifikat ke {$transaction->customer_email}: " . $e->getMessage());
        }

        return redirect()->back()->with('success', "E-Sertifikat Kehadiran untuk {$transaction->customer_name} berhasil diterbitkan dan dikirimkan ke email {$transaction->customer_email}!");
    }
}
