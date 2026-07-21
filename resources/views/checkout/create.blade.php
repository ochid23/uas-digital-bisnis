@extends('layouts.app')
@section('title', 'Checkout - ' . $event->title)
@section('content')
<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="mb-12">
        <a href="{{ route('events.show', $event->id) }}" class="text-zinc-400 hover:text-white transition font-bold flex items-center gap-2 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Event
        </a>
        <h1 class="text-4xl font-extrabold text-white">Checkout</h1>
        <p class="text-zinc-500 mt-2">Lengkapi data Anda untuk mendapatkan tiket.</p>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-900/50 border border-red-800 text-red-400 rounded-xl font-bold">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        <!-- Summary Card -->
        <div class="bg-zinc-900 rounded-3xl border border-zinc-800 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 border-b border-zinc-800 pb-4 text-white">Pesanan Anda</h3>
            <div class="flex gap-6 items-start">
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                ? asset('storage/' . $event->poster_path)
                : 'https://placehold.co/200x200' }}"
                alt="Event" class="w-24 h-24 rounded-2xl object-cover border border-zinc-700">
                <div>
                    <h4 class="font-extrabold text-lg text-white">{{ $event->title }}</h4>
                    <p class="text-zinc-400">{{ $event->date->format('d M Y') }} • {{ $event->location }}</p>
                    <p class="text-indigo-400 font-bold mt-2">1 x Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-zinc-800 space-y-3">
                <div class="flex justify-between text-zinc-400">
                    <span>Harga Tiket</span>
                    <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-zinc-400">
                    <span>Biaya Layanan</span>
                    <span>Rp 5.000</span>
                </div>
                <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t border-zinc-800">
                    <span class="text-white">Total Bayar</span>
                    <span class="text-indigo-400">Rp {{ number_format($event->price + 5000, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-zinc-900 rounded-3xl border border-zinc-800 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 italic text-zinc-300 underline underline-offset-8 decoration-zinc-700">📦 Data Pemesan (Tanpa Login)</h3>
            <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="customer_name" placeholder="Masukkan nama sesuai identitas" class="w-full px-5 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-zinc-100 placeholder:text-zinc-600 transition font-medium" required value="{{ old('customer_name') }}">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wide">Email Aktif</label>
                        <input type="email" name="customer_email" placeholder="contoh@gmail.com" class="w-full px-5 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-zinc-100 placeholder:text-zinc-600 transition font-medium" required value="{{ old('customer_email') }}">
                        <p class="text-[10px] text-zinc-500 mt-2 font-bold uppercase tracking-tighter">*E-Ticket akan dikirim ke email ini</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wide">No. WhatsApp</label>
                        <input type="tel" name="customer_phone" placeholder="08xxxxxxx" class="w-full px-5 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-zinc-100 placeholder:text-zinc-600 transition font-medium" required value="{{ old('customer_phone') }}">
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 active:scale-95 transition-all">
                    Lanjut Pembayaran
                </button>
                <p class="text-center text-xs text-zinc-500">Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.</p>
            </form>
        </div>
    </div>
</main>
@endsection