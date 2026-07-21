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
            'stock'       => 'required|integer|min:0', // Validasi stock ditambahkan
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ubah ke poster_path
        ]);

        $validatedData['organizer_id'] = Auth::id();

        // Proses upload gambar menggunakan nama kolom yang benar
        if ($request->hasFile('poster_path')) {
            $validatedData['poster_path'] = $request->file('poster_path')->store('posters', 'public');
        }

        Event::create($validatedData);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dibuat!');
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
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Proses upload gambar baru dan hapus gambar lama
        if ($request->hasFile('poster_path')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $validatedData['poster_path'] = $request->file('poster_path')->store('posters', 'public');
        }

        $event->update($validatedData);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil diperbarui!');
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