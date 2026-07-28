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
     * Mengirimkan pesan teks/media ke target nomor WhatsApp via API Fonnte.
     */
    public static function sendMessage(string $targetPhone, string $message, ?string $mediaUrl = null): bool
    {
        $token = config('services.fonnte.token', env('FONNTE_TOKEN', 'MgcXBjDDRhYLtD6B4Lk6'));
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
            $payload = [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62',
            ];

            if ($mediaUrl) {
                $payload['url'] = $mediaUrl;
            }

            $response = Http::asForm()->withHeaders([
                'Authorization' => $token,
            ])->post($url, $payload);

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
     * Kirim Notifikasi E-Ticket via WhatsApp setelah pembayaran berhasil (Lengkap dengan Gambar QR Code).
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
        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($transaction->order_id);

        $message = "Halo Kak *{$transaction->customer_name}*! 🎉\n\n"
                 . "Pembayaran untuk pemesanan tiket Anda telah *BERHASIL*.\n\n"
                 . "📋 *Detail Transaksi:*\n"
                 . "• Order ID: `{$transaction->order_id}`\n"
                 . "• Event: *{$eventTitle}*\n"
                 . "• Total Pembayaran: {$formattedPrice}\n"
                 . "• Status: LUNAS\n\n"
                 . "🎫 *E-Ticket Resmi:*\n"
                 . "Gambar di atas adalah Kode QR E-Ticket Anda untuk scan di lokasi acara.\n"
                 . "Link E-Ticket: {$successUrl}\n\n"
                 . "Terima kasih! Sampai jumpa di lokasi acara. 🚀";

        $sent = self::sendMessage($transaction->customer_phone, $message, $qrImageUrl);

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
                 . "Pemesanan tiket Anda untuk *{$eventTitle}* telah kami catat.\n\n"
                 . "📋 *Detail Pesanan Pending:*\n"
                 . "• Order ID: `{$transaction->order_id}`\n"
                 . "• Total Pembayaran: {$formattedPrice}\n\n"
                 . "💡 *Tidak sengaja menutup tab browser atau belum menyelesaikan pembayaran?*\n"
                 . "Jangan khawatir! Anda dapat melanjutkan dan menyelesaikan pembayaran tiket Anda kapan saja melalui link resmi berikut:\n\n"
                 . "👉 {$paymentUrl}\n\n"
                 . "Terima kasih! Jika membutuhkan bantuan, silakan balasi pesan ini. 🙏";

        $sent = self::sendMessage($transaction->customer_phone, $message);

        if ($sent) {
            $transaction->update(['wa_reminder_sent_at' => now()]);
        }

        return $sent;
    }
}
