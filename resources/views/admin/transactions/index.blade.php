@extends('layouts.admin')
@section('title', 'Laporan Transaksi - naazhi.')
@section('page_title', 'Laporan Transaksi')
@section('page_subtitle', 'Pantau arus kas dan penjualan tiket Anda.')

@section('content')
<div class="bg-zinc-900 rounded-[2.5rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-zinc-950/50 text-zinc-500 uppercase text-[10px] font-black tracking-widest border-b border-zinc-800">
                <tr>
                    <th class="px-8 py-5">Order ID</th>
                    <th class="px-8 py-5">Detail Pembeli</th>
                    <th class="px-8 py-5">Event</th>
                    <th class="px-8 py-5">Tgl Transaksi</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Total Tagihan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse($transactions as $trx)
                <tr class="hover:bg-zinc-800/30 transition-all duration-200 {{ $trx->status == 'pending' ? 'text-zinc-500 opacity-75' : '' }}">
                    <td class="px-8 py-6">
                        <span class="font-mono font-bold px-3 py-1.5 rounded-lg text-sm {{ $trx->status == 'pending' ? 'bg-zinc-800/50 text-zinc-400 border border-zinc-700/50' : 'text-indigo-400 bg-indigo-500/10 border border-indigo-500/20' }}">
                            {{ $trx->order_id }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-black {{ $trx->status == 'pending' ? 'text-zinc-400' : 'text-white' }} text-base mb-0.5">{{ $trx->customer_name }}</p>
                        <p class="text-xs text-zinc-500 font-medium">{{ $trx->customer_email }}<br>{{ $trx->customer_phone }}</p>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-bold {{ $trx->status == 'pending' ? 'text-zinc-500' : 'text-zinc-300' }}">{{ $trx->event->title ?? '-' }}</p>
                    </td>
                    <td class="px-8 py-6 text-sm text-zinc-400 font-medium">
                        {{ $trx->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-8 py-6">
                        @if($trx->status === 'settlement' || $trx->status === 'success')
                            <span class="px-3 py-1.5 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-black uppercase tracking-wider ring-1 ring-emerald-500/20 shadow-sm shadow-emerald-500/10">Success</span>
                        @elseif($trx->status === 'pending')
                            <span class="px-3 py-1.5 bg-amber-500/10 text-amber-400 rounded-lg text-xs font-black uppercase tracking-wider ring-1 ring-amber-500/20">Pending</span>
                        @else
                            <span class="px-3 py-1.5 bg-rose-500/10 text-rose-400 rounded-lg text-xs font-black uppercase tracking-wider ring-1 ring-rose-500/20">{{ $trx->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-right font-black {{ $trx->status == 'pending' ? 'text-zinc-500' : 'text-indigo-400' }} text-lg">
                        Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <div class="w-16 h-16 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-zinc-700/50">
                            <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-zinc-500 font-medium">Belum ada transaksi masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-8 py-5 bg-zinc-950/30 border-t border-zinc-800 items-center">
        {{ $transactions->links() }}
    </div>
</div>
@endsection