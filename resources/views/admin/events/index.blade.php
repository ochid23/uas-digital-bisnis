@extends('layouts.admin')
@section('title', 'Kelola Event - naazhi.')
@section('page_title', 'Kelola Event')
@section('page_subtitle', 'Buat dan atur acara seru Anda di sini.')

@section('content')
<div class="mb-6 text-right">
    <a href="{{ route('admin.events.create') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 active:scale-95 transition-all text-sm">
        + Tambah Event Baru
    </a>
</div>

<div class="bg-zinc-900 rounded-[2.5rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
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
                @forelse($events as $index => $event)
                <tr class="hover:bg-zinc-800/30 transition duration-200">
                    <td class="px-8 py-6 font-bold text-zinc-500">{{ $events->firstItem() + $index }}</td>
                    
                    <td class="px-8 py-6">
                        <img src="{{ $event->poster_url }}" class="w-16 h-20 rounded-xl object-cover shadow-md border border-zinc-700/50">
                    </td>
                    
                    <td class="px-8 py-6">
                        <p class="font-black text-white text-base mb-1">{{ $event->title }}</p>
                        <p class="text-xs text-zinc-500 font-medium">{{ $event->category->name ?? '-' }} • {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</p>
                    </td>
                    
                    <td class="px-8 py-6">
                        <p class="font-bold text-indigo-400 mb-1">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Stok: {{ $event->stock }}</p>
                    </td>
                    
                    <td class="px-8 py-6">
                        <div class="flex gap-2 justify-end">
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="p-2.5 bg-indigo-500/10 text-indigo-400 rounded-xl hover:bg-indigo-600 hover:text-white border border-indigo-500/20 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini?');">
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
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-16 text-center">
                        <div class="w-20 h-20 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-zinc-700/50">
                            <svg class="w-10 h-10 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <p class="text-zinc-500 font-medium">Belum ada acara yang ditambahkan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-8 py-5 bg-zinc-950/30 border-t border-zinc-800 items-center">
        {{ $events->links() }}
    </div>
</div>
@endsection