<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    protected $fillable = [
        'event_id', 'user_id', 'order_id', 'customer_name', 'customer_email', 'customer_phone',
        'total_price', 'status', 'snap_token', 'is_attended', 'attended_at',
        'certificate_code', 'certificate_sent_at', 'wa_sent_at', 'wa_reminder_sent_at'
    ];

    protected $casts = [
        'is_attended' => 'boolean',
        'attended_at' => 'datetime',
        'certificate_sent_at' => 'datetime',
        'wa_sent_at' => 'datetime',
        'wa_reminder_sent_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk membuat atau mengambil kode verifikasi E-Sertifikat unik.
     */
    public function generateCertificateCode(): string
    {
        if ($this->certificate_code) {
            return $this->certificate_code;
        }

        $code = 'CERT-AEH-' . date('Y') . '-' . strtoupper(substr(md5($this->order_id . time()), 0, 8));
        $this->update([
            'certificate_code' => $code
        ]);
        return $code;
    }

    /**
     * Helper static untuk melepaskan stok tiket (+1) dari transaksi pending yang kadaluarsa (> $minutes).
     */
    public static function releaseExpired(int $minutes = 15): int
    {
        $cutoffTime = now()->subMinutes($minutes);
        $expiredTransactions = self::with('event')
            ->where('status', 'pending')
            ->where('created_at', '<=', $cutoffTime)
            ->get();

        $releasedCount = 0;

        foreach ($expiredTransactions as $transaction) {
            try {
                DB::transaction(function () use ($transaction, &$releasedCount) {
                    $transaction->status = 'expired';
                    $transaction->save();

                    if ($transaction->event_id) {
                        $event = Event::where('id', $transaction->event_id)->lockForUpdate()->first();
                        if ($event) {
                            $event->increment('stock', 1);
                        }
                    }
                    $releasedCount++;
                    Log::info("Stok tiket dilepaskan (+1) untuk transaksi expired: {$transaction->order_id}");
                });
            } catch (\Exception $e) {
                Log::error("Gagal melepaskan stok tiket expired {$transaction->order_id}: " . $e->getMessage());
            }
        }

        return $releasedCount;
    }
}