<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Normalisasi format nomor telepon ke standar internasional Indonesia (62xxx).
     */
    public static function formatPhoneNumber(?string $phone): string
    {
        if (!$phone) {
            return '';
        }

        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali dengan '0', ubah menjadi '62'
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        // Jika diawali langsung angka '8', tambahkan '62'
        elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Mengirimkan pesan teks ke target nomor WhatsApp via API Fonnte.
     */
    public static function sendMessage(string $targetPhone, string $message): bool
    {
        $token = config('services.fonnte.token', env('FONNTE_TOKEN'));
        $url = config('services.fonnte.url', env('FONNTE_URL', 'https://api.fonnte.com/send'));

        if (empty($token)) {
            Log::warning('WhatsAppService: FONNTE_TOKEN tidak ditemukan di konfigurasi/environment.');
            return false;
        }

        $formattedPhone = self::formatPhoneNumber($targetPhone);
        if (empty($formattedPhone)) {
            Log::warning('WhatsAppService: Nomor telepon target tidak valid.', ['phone' => $targetPhone]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($url, [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsAppService: Pesan berhasil dikirim ke ' . $formattedPhone, [
                    'response' => $response->json()
                ]);
                return true;
            }

            Log::error('WhatsAppService: Gagal mengirim pesan ke ' . $formattedPhone, [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('WhatsAppService Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim Notifikasi E-Ticket via WhatsApp setelah pembayaran berhasil.
     */
    public static function sendTicketNotification(Transaction $transaction): bool
    {
        if (empty($transaction->customer_phone)) {
            return false;
        }

        // Cegah pengiriman ganda jika sudah pernah dikirim
        if ($transaction->wa_sent_at) {
            Log::info("WhatsApp E-Ticket sudah pernah dikirim untuk order {$transaction->order_id}");
            return true;
        }

        $eventTitle = $transaction->event ? $transaction->event->title : 'Event';
        $formattedPrice = $transaction->total_price == 0 ? 'GRATIS' : 'Rp ' . number_format($transaction->total_price, 0, ',', '.');
        $successUrl = route('checkout.success', $transaction->order_id);

        $message = "Halo Kak *{$transaction->customer_name}*! 🎉\n\n"
                 . "Pembayaran untuk pemesanan tiket Anda telah *BERHASIL*.\n\n"
                 . "📋 *Detail Transaksi:*\n"
                 . "• Order ID: `{$transaction->order_id}`\n"
                 . "• Event: *{$eventTitle}*\n"
                 . "• Total Pembayaran: {$formattedPrice}\n"
                 . "• Status: LUNAS\n\n"
                 . "🎫 *E-Ticket Anda:*\n"
                 . "Silakan klik link di bawah ini untuk melihat dan mengunduh E-Ticket Anda:\n"
                 . "{$successUrl}\n\n"
                 . "Terima kasih telah melakukan pemesanan! Sampai jumpa di lokasi acara. 🚀";

        $sent = self::sendMessage($transaction->customer_phone, $message);

        if ($sent) {
            $transaction->update(['wa_sent_at' => now()]);
        }

        return $sent;
    }

    /**
     * Kirim Pesan Pemulihan Transaksi (Abandoned Cart / Order Recovery).
     */
    public static function sendAbandonedCartReminder(Transaction $transaction): bool
    {
        if (empty($transaction->customer_phone)) {
            return false;
        }

        // Jangan kirim ulang jika sudah pernah dikirim
        if ($transaction->wa_reminder_sent_at) {
            return true;
        }

        $eventTitle = $transaction->event ? $transaction->event->title : 'Event';
        $formattedPrice = 'Rp ' . number_format($transaction->total_price, 0, ',', '.');
        $paymentUrl = route('checkout.payment', $transaction->order_id);

        $message = "Halo Kak *{$transaction->customer_name}*! 👋\n\n"
                 . "Kami mendeteksi pemesanan tiket Anda untuk *{$eventTitle}* belum diselesaikan nih.\n\n"
                 . "📋 *Detail Pesanan Hangus:*\n"
                 . "• Order ID: `{$transaction->order_id}`\n"
                 . "• Total: {$formattedPrice}\n\n"
                 . "⚠️ *Jangan sampai kehabisan tiket!* Anda masih bisa melanjutkan pembayaran secara instan dengan mengeklik tombol/link berikut:\n\n"
                 . "👉 {$paymentUrl}\n\n"
                 . "Jika Anda membutuhkan bantuan, silakan balasi pesan ini. Terima kasih! 🙏";

        $sent = self::sendMessage($transaction->customer_phone, $message);

        if ($sent) {
            $transaction->update(['wa_reminder_sent_at' => now()]);
        }

        return $sent;
    }
}
