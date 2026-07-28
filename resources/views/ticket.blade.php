@extends('layouts.app')

@section('title', 'Tiket Saya - AmikomEventHub')

@section('content')
<main class="max-w-6xl mx-auto px-4 sm:px-6 py-10 min-h-screen">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-zinc-800 pb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2.5 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Tiket Saya</h1>
            </div>
            <p class="text-zinc-400 text-sm">Kelola dan akses E-Ticket resmi serta E-Sertifikat untuk semua acara yang Anda ikuti.</p>
        </div>
        <a href="{{ url('/#events') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-xs shadow-lg shadow-indigo-600/20 transition active:scale-95 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Jelajahi Acara Lainnya
        </a>
    </div>

    @if($transactions->isEmpty())
        <!-- State jika belum ada tiket -->
        <div class="bg-zinc-900/60 border border-zinc-800 rounded-3xl p-12 text-center max-w-xl mx-auto shadow-2xl my-12">
            <div class="w-20 h-20 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-black text-white mb-2">Belum Ada Tiket Acara</h3>
            <p class="text-zinc-400 text-sm mb-8 leading-relaxed">
                Anda belum memiliki riwayat reservasi tiket. Temukan konser, seminar, dan acara seru lainnya di AmikomEventHub!
            </p>
            <a href="{{ url('/#events') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold rounded-2xl shadow-xl shadow-indigo-600/25 transition text-sm">
                Jelajahi Acara Sekarang
            </a>
        </div>
    @else
        <!-- Daftar Tiket User -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($transactions as $trx)
                <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl relative flex flex-col justify-between hover:border-zinc-700 transition">
                    
                    <!-- Header Card Acara & Status -->
                    <div>
                        <div class="relative h-44 w-full bg-zinc-950 overflow-hidden">
                            @if($trx->event && $trx->event->poster_url)
                                <img src="{{ $trx->event->poster_url }}" alt="{{ $trx->event->title }}" class="w-full h-full object-cover opacity-60">
                                <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/40 to-transparent"></div>
                            @else
                                <div class="w-full h-full bg-indigo-950/40 flex items-center justify-center">
                                    <span class="text-zinc-600 text-xs font-bold uppercase tracking-widest">AmikomEventHub Ticket</span>
                                </div>
                            @endif

                            <!-- Status Badge Overlay -->
                            <div class="absolute top-4 right-4 z-10">
                                @if(in_array($trx->status, ['settlement', 'success']))
                                    <span class="px-3.5 py-1.5 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider shadow-lg flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Lunas / Aktif
                                    </span>
                                @elseif($trx->status === 'pending')
                                    <span class="px-3.5 py-1.5 bg-amber-500/20 border border-amber-500/40 text-amber-300 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider shadow-lg flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                        Menunggu Pembayaran
                                    </span>
                                @else
                                    <span class="px-3.5 py-1.5 bg-rose-500/20 border border-rose-500/40 text-rose-300 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider shadow-lg">
                                        {{ strtoupper($trx->status) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Judul & Kategori -->
                            <div class="absolute bottom-4 left-6 right-6">
                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest bg-indigo-500/20 border border-indigo-500/30 px-2.5 py-1 rounded-md">
                                    {{ $trx->event->category->name ?? 'Event' }}
                                </span>
                                <h3 class="text-xl font-black text-white mt-1.5 line-clamp-1 leading-tight">
                                    {{ $trx->event->title ?? 'Acara Tidak Ditemukan' }}
                                </h3>
                            </div>
                        </div>

                        <!-- Ticket Side Cut Styling -->
                        <div class="relative flex items-center px-6 py-2 bg-zinc-900 border-y border-dashed border-zinc-800">
                            <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-zinc-950 rounded-full border-r border-zinc-800"></div>
                            <div class="w-full border-t border-dashed border-zinc-800"></div>
                            <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-zinc-950 rounded-full border-l border-zinc-800"></div>
                        </div>

                        <!-- Detail Informasi Tiket -->
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Nama Pemegang Tiket</p>
                                    <p class="font-bold text-zinc-100 truncate">{{ $trx->customer_name }}</p>
                                </div>
                                <div>
                                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Nomor Order</p>
                                    <p class="font-mono font-bold text-indigo-400 text-xs">{{ $trx->order_id }}</p>
                                </div>
                                <div>
                                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Tanggal & Waktu</p>
                                    <p class="font-bold text-zinc-200 text-xs">
                                        {{ $trx->event && $trx->event->date ? \Carbon\Carbon::parse($trx->event->date)->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Lokasi</p>
                                    <p class="font-bold text-zinc-200 text-xs truncate">{{ $trx->event->location ?? '-' }}</p>
                                </div>
                            </div>

                            @if(in_array($trx->status, ['settlement', 'success']))
                                <!-- QR Code & Verification Area -->
                                <div class="bg-zinc-950 p-6 rounded-2xl border border-zinc-800 flex flex-col sm:flex-row items-center gap-6">
                                    <div class="p-2 bg-white rounded-2xl shadow-lg shrink-0">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($trx->order_id) }}" alt="QR Code E-Ticket" class="w-32 h-32">
                                    </div>
                                    <div class="text-center sm:text-left space-y-2 flex-1">
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-400 bg-indigo-500/10 px-2.5 py-1 rounded-md border border-indigo-500/20">
                                            E-Ticket QR Check-in
                                        </span>
                                        <p class="text-xs text-zinc-400 leading-relaxed">
                                            Tunjukkan kode QR ini kepada panitia acara saat memasuki lokasi untuk verifikasi kehadiran.
                                        </p>
                                        
                                        @if($trx->is_attended)
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-extrabold rounded-lg mt-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Kehadiran Terverifikasi
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($trx->is_attended || $trx->certificate_code)
                                    <a href="{{ route('certificate.show', $trx->certificate_code ?? $trx->order_id) }}" target="_blank" class="w-full py-3 px-4 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-2xl font-bold text-xs transition flex items-center justify-center gap-2">
                                        <span>📜</span> Lihat & Unduh E-Certificate Kehadiran
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action Card -->
                    <div class="p-6 pt-0 flex items-center gap-3">
                        @if($trx->status === 'pending')
                            <a href="{{ route('checkout.payment', $trx->order_id) }}" class="flex-1 py-3 px-4 bg-amber-500 hover:bg-amber-400 text-zinc-950 font-black rounded-xl text-center text-xs transition shadow-lg shadow-amber-500/20">
                                Selesaikan Pembayaran
                            </a>
                        @elseif(in_array($trx->status, ['settlement', 'success']))
                            <button onclick="printTicket('{{ $trx->order_id }}')" class="flex-1 py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak E-Ticket
                            </button>
                        @endif

                        @if($trx->event_id)
                            <a href="{{ route('event.show', $trx->event_id) }}" class="px-4 py-3 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold rounded-xl text-xs transition border border-zinc-700">
                                Detail Acara
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</main>

<script>
function printTicket(orderId) {
    window.print();
}
</script>
@endsection