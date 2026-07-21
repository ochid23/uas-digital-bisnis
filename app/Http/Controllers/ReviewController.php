<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $event->reviews()->create([
            'reviewer_name' => $request->reviewer_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim! Terima kasih atas partisipasi Anda.');
    }
}