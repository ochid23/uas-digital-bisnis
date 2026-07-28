<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id', Auth::id())->latest()->get();
        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'poster_url'  => 'nullable|url|max:1000'
        ]);

        $validatedData['organizer_id'] = Auth::id();
        $validatedData['status'] = 'pending';

        if ($request->filled('poster_url')) {
            $validatedData['poster_path'] = $request->poster_url;
        } elseif ($request->hasFile('poster_path')) {
            $cdnUrl = \App\Services\ImageUploadService::upload($request->file('poster_path'));
            $validatedData['poster_path'] = $cdnUrl ?: $request->file('poster_path')->store('posters', 'public');
        }

        Event::create($validatedData);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dibuat dan menunggu persetujuan (ACC) dari Admin!');
    }

    public function show(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat event ini.');
        }

        return view('organizer.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit event ini.');
        }

        $categories = Category::all();
        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah event ini.');
        }

        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'poster_url'  => 'nullable|url|max:1000'
        ]);

        // Reset status ke pending jika diedit agar Admin meninjau kembali
        $validatedData['status'] = 'pending';
        $validatedData['rejection_reason'] = null;

        if ($request->filled('poster_url')) {
            $validatedData['poster_path'] = $request->poster_url;
        } elseif ($request->hasFile('poster_path')) {
            $cdnUrl = \App\Services\ImageUploadService::upload($request->file('poster_path'));
            if ($cdnUrl) {
                $validatedData['poster_path'] = $cdnUrl;
            } else {
                if ($event->poster_path && !str_starts_with($event->poster_path, 'http')) {
                    Storage::disk('public')->delete($event->poster_path);
                }
                $validatedData['poster_path'] = $request->file('poster_path')->store('posters', 'public');
            }
        }

        $event->update($validatedData);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil diperbarui dan diajukan ulang untuk persetujuan (ACC) Admin.');
    }

    public function destroy(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus event ini.');
        }

        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dihapus!');
    }
}