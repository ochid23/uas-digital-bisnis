@extends('layouts.app')
@section('title', 'Pembayaran Berhasil')
@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-zinc-900 rounded-3xl border border-zinc-800 p-12 shadow-sm inline-block w-full max-w-md">
        <div class="w-24 h-24 bg-green-900/30 text-green-400 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-black mb-4 text-white">Terima Kasih!</h2>
        <p class="text-zinc-400 mb-8 leading-relaxed">
            Pembayaran untuk pesanan <strong class="text-zinc-200">{{ $transaction->order_id }}</strong> sedang diproses atau telah berhasil.
            E-Ticket akan dikirim ke email Anda (<strong class="text-zinc-200">{{ $transaction->customer_email }}</strong>) setelah pembayaran terkonfirmasi lunas.
        </p>
        @if($transaction->is_attended || $transaction->certificate_code)
            <div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/30 rounded-2xl">
                <p class="text-xs text-amber-400 font-bold uppercase mb-2">Kehadiran Divalidasi</p>
                <a href="{{ route('certificate.show', $transaction->certificate_code ?? $transaction->order_id) }}" target="_blank" class="inline-block px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-zinc-950 rounded-xl font-extrabold shadow-lg shadow-amber-500/20 transition text-sm">
                    📜 Lihat & Unduh E-Certificate Kehadiran
                </a>
            </div>
        @endif
        <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition">
            Kembali ke Beranda
        </a>
    </div>
</main>
@endsection