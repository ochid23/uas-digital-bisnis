@extends('layouts.admin')
@section('title', 'Kelola Event - naazhi.')
@section('page_title', 'Kelola & ACC Event')
@section('page_subtitle', 'Persetujuan (ACC) dan manajemen seluruh acara dari Organizer.')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <!-- Tab Filter Status -->
    <div class="flex flex-wrap gap-2 p-1.5 bg-zinc-900 border border-zinc-800 rounded-2xl">
        <a href="{{ route('admin.events.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'all' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            Semua Event
        </a>
        <a href="{{ route('admin.events.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusFilter === 'pending' ? 'bg-amber-500 text-zinc-950 shadow-md shadow-amber-500/20' : 'text-amber-400 hover:text-amber-300 hover:bg-amber-500/10' }}">
            Menunggu ACC
            @if($pendingCount > 0)
                <span class="px-2 py-0.5 text-[10px] font-black bg-amber-400 text-zinc-950 rounded-full animate-pulse">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.events.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'approved' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            Disetujui ({{ $approvedCount }})
        </a>
        <a href="{{ route('admin.events.index', ['status' => 'rejected']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'rejected' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/20' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            Ditolak ({{ $rejectedCount }})
        </a>
    </div>

    <a href="{{ route('admin.events.create') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 active:scale-95 transition-all text-sm">
        + Tambah Event Baru
    </a>
</div>

<div class="bg-zinc-900 rounded-[2.5rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-zinc-950/50 text-zinc-500 uppercase text-[10px] font-black tracking-widest border-b border-zinc-800">
                <tr>
                    <th class="px-6 py-5 w-12">No</th>
                    <th class="px-6 py-5">Poster</th>
                    <th class="px-6 py-5">Judul & Organizer</th>
                    <th class="px-6 py-5">Status ACC</th>
                    <th class="px-6 py-5">Harga / Stok</th>
                    <th class="px-6 py-5 text-right">Aksi & Wewenang</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse($events as $index => $event)
                <tr class="hover:bg-zinc-800/30 transition duration-200">
                    <td class="px-6 py-6 font-bold text-zinc-500 text-sm">{{ $events->firstItem() + $index }}</td>
                    
                    <td class="px-6 py-6">
                        <img src="{{ $event->poster_url }}" class="w-14 h-18 rounded-xl object-cover shadow-md border border-zinc-700/50">
                    </td>
                    
                    <td class="px-6 py-6">
                        <p class="font-black text-white text-base mb-1">{{ $event->title }}</p>
                        <p class="text-xs text-zinc-400 font-medium mb-1">{{ $event->category->name ?? '-' }} • {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-zinc-800 text-zinc-300 rounded-lg text-[11px] font-semibold">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ $event->organizer->name ?? 'Admin / Utama' }}
                        </span>
                    </td>

                    <td class="px-6 py-6">
                        @if($event->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 rounded-xl text-xs font-black animate-pulse">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                Menunggu ACC
                            </span>
                        @elseif($event->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs font-black">
                                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                Ditolak
                            </span>
                            @if($event->rejection_reason)
                                <p class="text-[11px] text-rose-400/80 mt-1 italic max-w-xs">"{{ $event->rejection_reason }}"</p>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs font-black">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                Disetujui (Aktif)
                            </span>
                        @endif
                    </td>
                    
                    <td class="px-6 py-6">
                        <p class="font-bold text-indigo-400 mb-1 text-sm">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-zinc-500 font-medium">Stok: {{ $event->stock }}</p>
                    </td>
                    
                    <td class="px-6 py-6">
                        <div class="flex items-center gap-2 justify-end">
                            <!-- Tombol Akses Wewenang Admin: ACC (Setujui) -->
                            @if($event->status !== 'approved')
                                <form action="{{ route('admin.events.approve', $event->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui (ACC) event ini agar publik dapat melihat dan membeli tiket?');">
                                    @csrf
                                    <button type="submit" title="ACC / Setujui Event" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs shadow-md shadow-emerald-600/20 flex items-center gap-1.5 transition active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        ACC
                                    </button>
                                </form>
                            @endif

                            <!-- Tombol Akses Wewenang Admin: Tolak -->
                            @if($event->status !== 'rejected')
                                <button type="button" onclick="openRejectModal({{ $event->id }}, '{{ addslashes($event->title) }}')" title="Tolak Event" class="px-3 py-2 bg-rose-500/10 text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-500/20 rounded-xl font-bold text-xs flex items-center gap-1 transition active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Tolak
                                </button>
                            @endif

                            <!-- Edit & Delete -->
                            <a href="{{ route('admin.events.edit', $event->id) }}" title="Edit Event" class="p-2 bg-zinc-800 text-zinc-300 rounded-xl hover:bg-indigo-600 hover:text-white border border-zinc-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Event" class="p-2 bg-zinc-800 text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white border border-zinc-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <div class="w-20 h-20 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-zinc-700/50">
                            <svg class="w-10 h-10 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <p class="text-zinc-500 font-medium">Belum ada acara dalam kategori filter ini.</p>
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

<!-- Modal Input Alasan Penolakan -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-xl font-black text-white mb-1">Tolak Pengajuan Event</h3>
        <p class="text-xs text-zinc-400 mb-4">Berikan alasan mengapa event <span id="rejectEventTitle" class="text-white font-bold"></span> ditolak.</p>
        
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Alasan Penolakan</label>
                <textarea name="rejection_reason" rows="3" required placeholder="Contoh: Deskripsi event kurang jelas, atau poster melanggar aturan..." class="w-full bg-zinc-950 border border-zinc-800 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-rose-500 transition"></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-zinc-800 text-zinc-300 hover:bg-zinc-700 rounded-xl text-xs font-bold transition">Batal</button>
                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-rose-600/20 transition">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(eventId, title) {
        document.getElementById('rejectEventTitle').innerText = title;
        document.getElementById('rejectForm').action = "/admin/events/" + eventId + "/reject";
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
</script>
@endsection