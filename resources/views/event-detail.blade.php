@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-1">
            <div class="sticky top-32">
                <img src="{{ $event->poster_url }}" alt="{{ $event->title }}"
                    class="w-full rounded-[2.5rem] shadow-2xl shadow-black/50 border-4 border-zinc-800 object-cover aspect-[3/4]">
                
                <div class="mt-8 p-6 bg-zinc-900 rounded-3xl border border-zinc-800 shadow-sm">
                    <h4 class="font-bold mb-4 text-zinc-300 uppercase text-xs tracking-wider">Penyelenggara</h4>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-indigo-500/20 border border-indigo-500/30 rounded-xl flex items-center justify-center text-indigo-400 font-bold">
                            nz</div>
                        <div>
                            <p class="font-bold text-white">{{ $event->organizer->name ?? 'naazhi Admin' }}</p>
                            <p class="text-xs text-zinc-500">Verified Organizer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-12">
            <div class="space-y-4">
                <span
                    class="px-4 py-1.5 bg-zinc-800 text-zinc-300 border border-zinc-700 rounded-full text-xs font-bold uppercase tracking-wider">{{ $event->category->name ?? 'Umum' }}</span>
                <h1 class="text-4xl md:text-5xl font-black leading-tight text-white">{{ $event->title }}</h1>
                <div class="flex flex-wrap gap-6 text-zinc-400 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('l, d M Y - H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <div class="prose prose-invert prose-zinc max-w-none">
                <h3 class="text-2xl font-bold mb-4 text-white">Deskripsi Event</h3>
                <p class="text-lg text-zinc-400 leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
            </div>

            <!-- Tiket Box Re-design Dark Mode -->
            <div
                class="bg-zinc-900 border border-zinc-800 rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-black/50 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <p class="text-zinc-500 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                        <h2 class="text-5xl font-black text-white">{{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }} <span class="text-lg font-medium text-zinc-500">/ orang</span></h2>
                        <p class="mt-4 text-zinc-400 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa stok: <span class="font-bold text-indigo-400">{{ $event->stock }} Tiket lagi!</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ url('checkout/' . $event->id) }}"
                            class="inline-block px-10 py-5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-black text-xl hover:scale-105 transition-all shadow-lg shadow-indigo-500/20">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                <!-- Dark Mode Blobs -->
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-600 opacity-10 rounded-full blur-3xl"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-purple-600 opacity-10 rounded-full blur-2xl"></div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white">Kebijakan Tiket</h3>
                <ul class="space-y-3 text-zinc-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Tiket dapat discan di pintu masuk (Check-in).
                    </li>
                    <li class="flex items-start gap-2 text-rose-400">
                        <svg class="w-5 h-5 text-rose-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tiket yang sudah dibeli tidak dapat direfund.
                    </li>
                </ul>
            </div>

            <!-- Bagian Ulasan -->
            <div class="mt-12 bg-zinc-900 rounded-3xl border border-zinc-800 p-8 shadow-sm">
                <h3 class="text-2xl font-black mb-6 text-white">Ulasan Pengunjung</h3>
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl font-bold">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('reviews.store', $event->id) }}" method="POST" class="mb-10 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" name="reviewer_name" placeholder="Nama Anda" class="w-full px-5 py-3 bg-zinc-950 border border-zinc-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-zinc-100 placeholder:text-zinc-600 transition" required>
                        </div>
                        <div>
                            <select name="rating" class="w-full px-5 py-3 bg-zinc-950 border border-zinc-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-zinc-100 transition" required>
                                <option value="5" class="bg-zinc-900">⭐⭐⭐⭐⭐ (5/5) Sangat Bagus</option>
                                <option value="4" class="bg-zinc-900">⭐⭐⭐⭐ (4/5) Bagus</option>
                                <option value="3" class="bg-zinc-900">⭐⭐⭐ (3/5) Cukup</option>
                                <option value="2" class="bg-zinc-900">⭐⭐ (2/5) Kurang</option>
                                <option value="1" class="bg-zinc-900">⭐ (1/5) Buruk</option>
                            </select>
                        </div>
                    </div>
                    <textarea name="comment" rows="3" placeholder="Bagaimana pengalaman Anda di acara ini?" class="w-full px-5 py-3 bg-zinc-950 border border-zinc-800 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-zinc-100 placeholder:text-zinc-600 transition" required></textarea>
                    <button type="submit" class="px-6 py-3 bg-zinc-800 border border-zinc-700 text-white rounded-xl font-bold hover:bg-indigo-600 hover:border-indigo-500 transition-all">Kirim Ulasan</button>
                </form>

                <div class="space-y-6">
                    @forelse($event->reviews()->latest()->get() as $review)
                        <div class="border-b border-zinc-800 pb-6 last:border-0">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-zinc-800 border border-zinc-700 rounded-full flex items-center justify-center text-zinc-300 font-bold uppercase">
                                    {{ substr($review->reviewer_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-200">{{ $review->reviewer_name }}</p>
                                    <p class="text-xs text-zinc-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="ml-auto text-amber-400 text-sm tracking-widest drop-shadow-md">
                                    {{ str_repeat('⭐', $review->rating) }}
                                </div>
                            </div>
                            <p class="text-zinc-400 pl-13 leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-zinc-950 border border-zinc-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <p class="text-zinc-400 font-medium">Belum ada ulasan.</p>
                            <p class="text-sm text-zinc-500">Jadilah yang pertama memberikan penilaian!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>
@endsection