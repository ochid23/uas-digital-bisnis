@extends('layouts.app')

@section('content')

    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span
                class="inline-block px-4 py-1.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full text-xs font-bold uppercase tracking-wider">
                Exclusive Event Platform
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight text-white tracking-tight">
                Temukan & Pesan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-zinc-400 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events"
                    class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transition-all">
                    Mulai Jelajah
                </a>
                <a href="#"
                    class="px-8 py-4 border border-zinc-700 text-zinc-300 rounded-2xl font-bold text-lg hover:border-indigo-500 hover:bg-zinc-900 transition-all">
                    Cara Pesan
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <!-- Glow Effect -->
            <div
                class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-600 rounded-full mix-blend-screen filter blur-[100px] opacity-30">
            </div>
            <div
                class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-600 rounded-full mix-blend-screen filter blur-[100px] opacity-30">
            </div>
            
            <img src="{{ asset('assets/concert.png') }}" alt="Concert"
                class="rounded-[2rem] shadow-2xl shadow-black/50 border border-zinc-800 relative z-10 w-full object-cover aspect-[4/5] object-center">

            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-2xl z-20 border border-zinc-700/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-full flex items-center justify-center text-emerald-400 border border-emerald-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-400 font-bold uppercase tracking-wider">Terverifikasi</p>
                        <p class="font-bold text-zinc-100">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="events" class="max-w-7xl mx-auto px-6 py-20 relative">
        <!-- Background subtle grid line -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdib3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAzNHYzLTRoNHYtNGgtNHYzem0wLTNoLTR2NGg0di00em0wLTN2LTRoNHY0aC00em0wLTNoLTRWMjBoNHY0em0wLThoLTR2NGg0di00em0tOC00djRoNHYtNGgtNHptMC0zaDRWMjBoLTR2NHptMC04aDRWMThoLTR2NHptMC04aDRWMTBoLTR2NHptLTgtNHY0aDRWNmgtNHptMC0zaDRWMmg0djRoNHY0aDRWNmgtNFYyaC00djRoLTRWMmg0djRoLTR2NGgtNFY2aC00djR6IiBmaWxsPSIjMmQyZDNmIiBmaWxsLW9wYWNpdHk9IjAuNSIvPjwvZz48L3N2Zz4=')] opacity-20 pointer-events-none"></div>

        <div class="flex justify-between items-end mb-12 relative z-10">
            <div>
                <h2 class="text-3xl font-extrabold mb-2 text-white">Event Terdekat</h2>
                <p class="text-zinc-400 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
            </div>
        </div>

        <div class="mb-10 flex gap-4 flex-wrap justify-start relative z-10">
            <a href="{{ url('/') }}#events" 
               class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all {{ !request('category') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'bg-zinc-900 border border-zinc-800 text-zinc-400 hover:border-indigo-500 hover:text-indigo-400' }}">
                Semua Kategori
            </a>
            
            @foreach($categories as $cat)
                <a href="{{ url('/?category=' . $cat->slug) }}#events" 
                   class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all {{ request('category') == $cat->slug ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'bg-zinc-900 border border-zinc-800 text-zinc-400 hover:border-indigo-500 hover:text-indigo-400' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative z-10">
            @forelse($events as $event)
            <div class="group bg-zinc-900 rounded-3xl border border-zinc-800 shadow-md shadow-black/20 hover:shadow-2xl hover:shadow-black/60 hover:border-zinc-700 transition-all duration-300 overflow-hidden flex flex-col">
                <div class="relative overflow-hidden aspect-[3/4]">
                    <img src="{{ ($event->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/400x600' }}" 
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-90 group-hover:opacity-100">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-transparent to-transparent opacity-80"></div>

                    <div class="absolute top-4 left-4 px-3 py-1 bg-zinc-900/80 backdrop-blur border border-zinc-700/50 rounded-lg text-xs font-bold uppercase text-indigo-400">
                        {{ $event->category->name ?? '-' }}
                    </div>
                </div>
                
                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-xl font-bold mb-2 text-white group-hover:text-indigo-400 transition-colors">
                        {{ $event->title }}
                    </h3>
                    
                    <div class="flex items-center gap-2 text-zinc-400 text-sm mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center mt-auto pt-5 border-t border-zinc-800">
                        <span class="text-2xl font-black text-indigo-400">
                            {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('events.show', $event->id) }}"
                            class="px-5 py-2.5 bg-zinc-800 border border-zinc-700 text-zinc-300 rounded-xl font-bold text-sm hover:bg-indigo-600 hover:border-indigo-500 hover:text-white transition-all">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20">
                <div class="bg-zinc-900/50 rounded-3xl p-12 border-2 border-dashed border-zinc-800">
                    <p class="text-zinc-500 font-medium text-lg">Belum ada event tersedia untuk kategori ini.</p>
                </div>
            </div>
            @endforelse
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-16 border-t border-zinc-800/50">
        <div class="text-center mb-10">
            <h2 class="text-xl font-bold text-zinc-500 uppercase tracking-widest text-sm">Telah Didukung Oleh Platform naazhi</h2>
        </div>
        
        <div class="flex flex-wrap justify-center items-end gap-10 opacity-50 hover:opacity-100 transition-opacity duration-500">
            @forelse($partners ?? [] as $partner)
                <div class="w-32 md:w-40 flex flex-col items-center justify-center grayscale hover:grayscale-0 transition duration-300 gap-3">
                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-16 object-contain brightness-0 invert">
                    <span class="text-xs font-bold text-zinc-400 text-center leading-tight">
                        {{ $partner->name }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-zinc-600">Belum ada partner yang ditambahkan.</p>
            @endforelse
        </div>
    </section>

@endsection