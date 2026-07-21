<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    // Catatan: Saya telah membersihkan spasi tersembunyi (non-breaking space) 
    // yang biasanya menyebabkan error "T_VARIABLE" jika di-copy-paste.
    protected $fillable = [
        'organizer_id', 'category_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}