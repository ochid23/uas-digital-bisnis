@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Ringkasan')

@section('content')
<!-- KARTU RINGKASAN STATISTIK -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-sm hover:border-zinc-700 transition">
        <div class="w-12 h-12 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
        </div>
        <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-sm hover:border-zinc-700 transition">
        <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                </path>
            </svg>
        </div>
        <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black text-white">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-sm hover:border-zinc-700 transition">
        <div class="w-12 h-12 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Event Aktif</p>
        <h3 class="text-2xl font-black text-white">{{ $activeEvents }} Event</h3>
    </div>
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-sm hover:border-zinc-700 transition">
        <div class="w-12 h-12 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Pesanan Pending</p>
        <h3 class="text-2xl font-black text-white">{{ $pendingOrders }} Pesanan</h3>
    </div>
</div>

<!-- AREA GRAFIK CHART.JS -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-sm">
        <div class="border-b border-zinc-800 pb-4 mb-4">
            <h3 class="font-black text-xl text-white">Pertumbuhan Pengguna Baru</h3>
        </div>
        <div>
            <canvas id="userChart" height="200"></canvas>
        </div>
    </div>
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-sm">
        <div class="border-b border-zinc-800 pb-4 mb-4">
            <h3 class="font-black text-xl text-white">Tren Penyelenggaraan Event</h3>
        </div>
        <div>
            <canvas id="eventChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- TABEL RIWAYAT TRANSAKSI TERAKHIR -->
<div class="bg-zinc-900 rounded-3xl border border-zinc-800 shadow-sm overflow-hidden mb-10">
    <div class="p-8 border-b border-zinc-800 flex justify-between items-center">
        <h3 class="font-black text-xl text-white">Transaksi Terakhir</h3>
        <a href="{{ route('admin.transactions.index') }}" class="text-indigo-400 font-bold hover:text-indigo-300 transition">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-zinc-950/50 text-zinc-500 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-1/4">Tgl Transaksi</th>
                    <th class="px-8 py-4 w-1/4">Pembeli</th>
                    <th class="px-8 py-4 w-1/4">Event</th>
                    <th class="px-8 py-4 w-[10%]">Status</th>
                    <th class="px-8 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800 border-t border-zinc-800">
                @forelse($recentTransactions as $trx)
                <tr class="hover:bg-zinc-800/50 transition">
                    <td class="px-8 py-6 text-sm text-zinc-400 max-w-xs break-all">{{ $trx->created_at->format('d M y - H:i') }}<br><span class="text-xs text-zinc-600">{{ $trx->order_id }}</span></td>
                    <td class="px-8 py-6">
                        <p class="font-bold uppercase tracking-wide text-sm text-zinc-200 truncate max-w-[150px]">{{ $trx->customer_name }}</p>
                        <p class="text-xs text-zinc-500 truncate max-w-[150px]">{{ $trx->customer_email }}</p>
                    </td>
                    <td class="px-8 py-6 font-medium text-zinc-400 max-w-xs truncate">{{ $trx->event->title ?? '-' }}</td>
                    <td class="px-8 py-6 whitespace-nowrap">
                        @if($trx->status === 'settlement' || $trx->status === 'success')
                        <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg text-xs font-bold uppercase">Success</span>
                        @elseif($trx->status === 'pending')
                        <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-lg text-xs font-bold uppercase">Pending</span>
                        @else
                        <span class="px-3 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-lg text-xs font-bold uppercase">{{ $trx->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 font-black text-indigo-400 whitespace-nowrap text-right">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-zinc-500">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SCRIPT CHART.JS DI BAGIAN BAWAH -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Chart.defaults.color = '#71717a'; // Ubah warna default text Chart jadi zinc-500
        Chart.defaults.borderColor = '#27272a'; // Ubah warna garis grid jadi zinc-800

        const labels = JSON.parse('{!! json_encode($months) !!}');
        const userData = JSON.parse('{!! json_encode($userChartData) !!}');
        const eventData = JSON.parse('{!! json_encode($eventChartData) !!}');

        const ctxUser = document.getElementById('userChart').getContext('2d');
        new Chart(ctxUser, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pengguna Baru',
                    data: userData,
                    borderColor: '#818cf8', /* Indigo 400 */
                    backgroundColor: 'rgba(129, 140, 248, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#818cf8',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                plugins: { legend: { display: false } }
            }
        });

        const ctxEvent = document.getElementById('eventChart').getContext('2d');
        new Chart(ctxEvent, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Event Diselenggarakan',
                    data: eventData,
                    backgroundColor: 'rgba(52, 211, 153, 0.8)', /* Emerald 400 */
                    borderColor: '#34d399',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection