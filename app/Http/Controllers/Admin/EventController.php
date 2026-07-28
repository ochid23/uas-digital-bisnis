<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organizer']);
        
        // Filter Multi-Tenant: Organizer hanya melihat event miliknya jika diakses lewat rute ini
        if (Auth::user()->role !== 'admin') {
            $query->where('organizer_id', Auth::id());
        }

        // Filter berdasarkan Tab Status
        $statusFilter = $request->query('status', 'all');
        if ($statusFilter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($statusFilter === 'approved') {
            $query->where(function ($q) {
                $q->where('status', 'approved')->orWhereNull('status');
            });
        } elseif ($statusFilter === 'rejected') {
            $query->where('status', 'rejected');
        }

        $events = $query->latest()->paginate(10)->withQueryString();

        // Hitung total pending untuk informasi badge tab
        $pendingCount = Event::where('status', 'pending')->count();
        $approvedCount = Event::where(function ($q) {
            $q->where('status', 'approved')->orWhereNull('status');
        })->count();
        $rejectedCount = Event::where('status', 'rejected')->count();

        return view('admin.events.index', compact('events', 'statusFilter', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:5120',
            'poster_url'  => 'nullable|url|max:1000'
        ]);

        if ($request->filled('poster_url')) {
            $data['poster_path'] = $request->poster_url;
        } elseif ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        // Event yang dibuat langsung oleh Admin otomatis disetujui
        $data['organizer_id'] = Auth::id();
        $data['status'] = 'approved';

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan dan otomatis disetujui (ACC).');
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:5120',
            'poster_url'  => 'nullable|url|max:1000'
        ]);

        if ($request->filled('poster_url')) {
            $data['poster_path'] = $request->poster_url;
        } elseif ($request->hasFile('poster')) {
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function approve(Event $event)
    {
        $event->update([
            'status' => 'approved',
            'rejection_reason' => null
        ]);

        return redirect()->back()->with('success', "Event '{$event->title}' berhasil disetujui (ACC) dan sekarang tampil publik.");
    }

    public function reject(Request $request, Event $event)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi.'
        ]);

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', "Event '{$event->title}' telah ditolak.");
    }

    public function destroy(Event $event)
    {
        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }
        
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Data event dan poster berhasil dihapus secara permanen.');
    }
}