@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-white">Manajemen Event</h2>
            <p class="text-zinc-400 mt-1 text-sm">Kelola seluruh daftar acara yang Anda selenggarakan.</p>
        </div>
        <a href="{{ route('organizer.events.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition-all text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Event
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl mb-6 font-bold text-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-zinc-900 rounded-[2rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-zinc-950/50 text-zinc-500 uppercase text-[10px] font-black tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-8 py-5">Judul Event & Lokasi</th>
                        <th class="px-6 py-5">Tanggal</th>
                        <th class="px-6 py-5">Harga</th>
                        <th class="px-6 py-5">Stok</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    @forelse ($events as $event)
                        <tr class="hover:bg-zinc-800/30 transition duration-200">
                            <td class="px-8 py-6">
                                <div class="font-black text-white text-base mb-1">{{ $event->title }}</div>
                                <div class="text-xs text-zinc-500 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $event->location }}
                                </div>
                            </td>
                            <td class="px-6 py-6 text-sm text-zinc-400 font-medium">
                                {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-6">
                                <span class="font-bold text-indigo-400">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-6 text-sm text-zinc-400 font-medium">
                                {{ $event->stock }} Tiket
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('organizer.events.edit', $event->id) }}" class="p-2.5 bg-indigo-500/10 text-indigo-400 rounded-xl hover:bg-indigo-600 hover:text-white border border-indigo-500/20 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus event ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-rose-500/10 text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white border border-rose-500/20 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-zinc-700/50">
                                    <svg class="w-10 h-10 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-zinc-500 font-medium mb-4">Belum ada event yang Anda buat.</p>
                                <a href="{{ route('organizer.events.create') }}" class="inline-block px-5 py-2.5 bg-zinc-800 text-white rounded-xl font-bold hover:bg-indigo-600 border border-zinc-700 hover:border-indigo-500 transition-all text-sm">
                                    + Buat Event Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection