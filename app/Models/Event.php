<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'organizer_id', 'category_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path', 'status', 'rejection_reason'
    ];

    public function scopeApproved($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'approved')
              ->orWhereNull('status');
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Accessor untuk mendapatkan URL poster secara dinamis dari database.
     */
    public function getPosterUrlAttribute(): string
    {
        if (!$this->poster_path) {
            return 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=600&auto=format&fit=crop&q=80';
        }

        // Jika isi database berupa Data URI (base64) atau URL penuh (http:// atau https://)
        if (str_starts_with($this->poster_path, 'data:image') || str_starts_with($this->poster_path, 'http://') || str_starts_with($this->poster_path, 'https://')) {
            return $this->poster_path;
        }

        return asset('storage/' . ltrim($this->poster_path, '/'));
    }

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