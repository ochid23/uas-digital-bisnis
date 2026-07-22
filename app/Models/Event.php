<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'organizer_id', 'category_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Accessor untuk mendapatkan URL poster secara dinamis dari database.
     */
    public function getPosterUrlAttribute(): string
    {
        if (!$this->poster_path) {
            return 'https://placehold.co/400x600?text=No+Poster';
        }

        // Jika isi database berupa URL penuh (http:// atau https://)
        if (str_starts_with($this->poster_path, 'http://') || str_starts_with($this->poster_path, 'https://')) {
            return $this->poster_path;
        }

        // Kembalikan URL aset file yang tersimpan di storage publik sesuai database
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