<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'event_id', 'order_id', 'customer_name', 'customer_email', 'customer_phone',
        'total_price', 'status', 'snap_token', 'is_attended', 'attended_at',
        'certificate_code', 'certificate_sent_at'
    ];

    protected $casts = [
        'is_attended' => 'boolean',
        'attended_at' => 'datetime',
        'certificate_sent_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
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
}