<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReleaseExpiredTickets extends Command
{
    /**
     * Nama dan tanda tangan dari perintah console.
     *
     * @var string
     */
    protected $signature = 'tickets:release-expired {--minutes=15 : Batas waktu menit reservasi pending}';

    /**
     * Deskripsi dari perintah console.
     *
     * @var string
     */
    protected $description = 'Lepaskan kembali stok tiket (+1) dari transaksi pending yang telah kadaluarsa/terbengkalai.';

    /**
     * Eksekusi perintah console.
     */
    public function handle()
    {
        $minutes = (int) $this->option('minutes');
        $cutoffTime = now()->subMinutes($minutes);

        $this->info("Memindai transaksi pending yang melewati batas reservasi ({$minutes} menit)...");

        $expiredTransactions = Transaction::with('event')
            ->where('status', 'pending')
            ->where('created_at', '<=', $cutoffTime)
            ->get();

        if ($expiredTransactions->isEmpty()) {
            $this->info("Tidak ada transaksi pending yang kadaluarsa saat ini.");
            return 0;
        }

        $releasedCount = 0;

        foreach ($expiredTransactions as $transaction) {
            DB::transaction(function () use ($transaction, &$releasedCount) {
                // Ubah status transaksi menjadi expired
                $transaction->status = 'expired';
                $transaction->save();

                // Kembalikan stok tiket ke event terkait (+1)
                if ($transaction->event) {
                    $event = Event::where('id', $transaction->event_id)->lockForUpdate()->first();
                    if ($event) {
                        $event->increment('stock', 1);
                    }
                }

                $releasedCount++;
                Log::info("Stok tiket dilepaskan (+1) untuk transaksi expired: {$transaction->order_id}");
            });

            $this->info("  [BERHASIL] Tiket dilepaskan kembali ke stok untuk Order: {$transaction->order_id}");
        }

        $this->info("Selesai! Total {$releasedCount} tiket berhasil dilepaskan kembali ke publik.");
        return 0;
    }
}
