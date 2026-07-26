<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendAbandonedCartReminder extends Command
{
    /**
     * Nama dan tanda tangan dari perintah console.
     *
     * @var string
     */
    protected $signature = 'reminder:abandoned-cart {--minutes=15 : Menit minimal transaksi terbengkalai}';

    /**
     * Deskripsi dari perintah console.
     *
     * @var string
     */
    protected $description = 'Kirim pesan pengingat pemulihan pembayaran (Abandoned Cart Recovery) via WhatsApp untuk transaksi pending.';

    /**
     * Eksekusi perintah console.
     */
    public function handle()
    {
        $minutes = (int) $this->option('minutes');
        $cutoffTime = now()->subMinutes($minutes);
        $maxAgeTime = now()->subHours(24);

        $this->info("Memulai pencarian transaksi pending yang terbengkalai (lebih dari {$minutes} menit lalu)...");

        $pendingTransactions = Transaction::with('event')
            ->where('status', 'pending')
            ->where('created_at', '<=', $cutoffTime)
            ->where('created_at', '>=', $maxAgeTime)
            ->whereNull('wa_reminder_sent_at')
            ->get();

        if ($pendingTransactions->isEmpty()) {
            $this->info("Tidak ada transaksi pending yang perlu dikirimi pengingat WhatsApp saat ini.");
            return 0;
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($pendingTransactions as $transaction) {
            $this->line("Mengirim WA reminder ke: {$transaction->customer_name} ({$transaction->customer_phone}) - Order: {$transaction->order_id}");

            $success = WhatsAppService::sendAbandonedCartReminder($transaction);

            if ($success) {
                $sentCount++;
                $this->info("  [BERHASIL] WA Recovery terkirim ke {$transaction->order_id}");
            } else {
                $failedCount++;
                $this->error("  [GAGAL] Gagal mengirim WA Recovery ke {$transaction->order_id}");
            }
        }

        $this->info("Selesai! Berhasil mengirim {$sentCount} pengingat, Gagal: {$failedCount}.");
        return 0;
    }
}
