@extends('layouts.app') {{-- Pastikan layout ini sudah menggunakan versi Dark Mode --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
       <h2 class="text-2xl font-black text-white">Dashboard Organizer</h2>
       <p class="text-zinc-400 text-sm mt-1">Selamat datang kembali, <span class="text-indigo-400 font-bold">{{ Auth::user()->name }}</span>! Berikut adalah ringkasan data kepanitiaan Anda.</p>
    </div>

    <!-- Grid Statistik Dasar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card: Total Event -->
        <div class="bg-zinc-900 rounded-2xl shadow-lg shadow-black/20 border border-zinc-800 p-6 hover:border-zinc-700 transition-all">
            <div class="flex items-center">
                <div class="p-4 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <h2 class="text-xs font-bold tracking-widest text-zinc-500 uppercase">Total Event Aktif</h2>
                    <p class="text-2xl font-black text-white mt-1">{{ $activeEvents }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Tiket Terjual -->
        <div class="bg-zinc-900 rounded-2xl shadow-lg shadow-black/20 border border-zinc-800 p-6 hover:border-zinc-700 transition-all">
            <div class="flex items-center">
                <div class="p-4 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <h2 class="text-xs font-bold tracking-widest text-zinc-500 uppercase">Tiket Terjual</h2>
                    <p class="text-2xl font-black text-white mt-1">{{ $ticketsSold }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Pendapatan -->
        <div class="bg-zinc-900 rounded-2xl shadow-lg shadow-black/20 border border-zinc-800 p-6 hover:border-zinc-700 transition-all">
            <div class="flex items-center">
                <div class="p-4 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <h2 class="text-xs font-bold tracking-widest text-zinc-500 uppercase">Estimasi Pendapatan</h2>
                    <p class="text-2xl font-black text-white mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Konten Utama Dasbor (Tabel Event Serapi Admin) -->
    <div class="bg-zinc-900 rounded-[2.5rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
        <div class="flex justify-between items-center px-8 py-6 border-b border-zinc-800">
            <h3 class="text-lg font-black text-white">Manajemen Cepat</h3>
            <a href="{{ route('organizer.events.index') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-bold transition">Lihat Semua Event &rarr;</a>
        </div>
        
        @if($recentEvents->isEmpty())
            <!-- Tampilan Jika Belum Ada Event -->
            <div class="text-center py-16 px-6 text-zinc-500">
                <div class="w-20 h-20 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-zinc-700/50">
                    <svg class="w-10 h-10 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="mb-6 font-medium">Anda belum memiliki event. Kelola acara Anda dengan mudah melalui panel ini.</p>
                <a href="{{ route('organizer.events.create') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition">
                    + Buat Event Baru
                </a>
            </div>
        @else
            <!-- Tampilan Tabel Event -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-zinc-950/50 text-zinc-500 uppercase text-[10px] font-black tracking-widest border-b border-zinc-800">
                        <tr>
                            <th class="px-8 py-5 w-16">No</th>
                            <th class="px-8 py-5">Poster</th>
                            <th class="px-8 py-5">Event</th>
                            <th class="px-8 py-5">Harga / Stok</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50">
                        @foreach($recentEvents as $index => $event)
                        <tr class="hover:bg-zinc-800/30 transition duration-200">
                            <td class="px-8 py-6 font-bold text-zinc-500">{{ $loop->iteration }}</td>
                            
                            <td class="px-8 py-6">
                                <img src="{{ $event->poster_url }}" class="w-16 h-20 rounded-xl object-cover shadow-md border border-zinc-700/50">
                            </td>
                            
                            <td class="px-8 py-6">
                                <p class="font-black text-zinc-100 text-base mb-1">{{ $event->title ?? $event->name }}</p>
                                <p class="text-xs text-zinc-500 font-medium">{{ $event->category->name ?? '-' }} • {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</p>
                            </td>
                            
                            <td class="px-8 py-6">
                                <p class="font-bold text-indigo-400 mb-1">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-zinc-500 font-medium">Stok: {{ $event->stock }}</p>
                            </td>
                            
                            <td class="px-8 py-6">
                                <div class="flex gap-2 justify-end">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('organizer.events.edit', $event->id) }}" class="p-2.5 bg-indigo-500/10 text-indigo-400 rounded-xl hover:bg-indigo-600 hover:text-white border border-indigo-500/20 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-rose-500/10 text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white border border-rose-500/20 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Area Bawah Tabel -->
            <div class="px-8 py-5 bg-zinc-950/30 border-t border-zinc-800 flex justify-end">
                <a href="{{ route('organizer.events.create') }}" class="inline-block px-5 py-2.5 bg-zinc-800 text-white rounded-xl font-bold hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-500/20 border border-zinc-700 hover:border-indigo-500 transition text-sm">
                    + Tambah Event
                </a>
            </div>
        @endif
    </div>
</div>
@endsection