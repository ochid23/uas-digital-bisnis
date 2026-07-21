@extends('layouts.admin')
@section('title', 'Laporan Transaksi - Admin')
@section('page_title', 'Laporan Transaksi')
@section('page_subtitle', 'Pantau arus kas dan penjualan tiket Anda.')

@section('content')

        <div class="bg-zinc-900 rounded-[2.5rem] border border-zinc-800 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-zinc-950/50 border-b border-zinc-800 flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[300px] flex gap-2">
                    <input type="text" placeholder="Cari Order ID, Nama, atau Email..."
                        class="flex-1 px-5 py-3 rounded-xl border border-zinc-800 bg-zinc-950 text-zinc-100 placeholder:text-zinc-600 focus:ring-1 focus:ring-indigo-500 outline-none transition uppercase text-sm font-medium tracking-wide">
                </div>
                <div class="flex gap-2">
                    <select
                        class="px-5 py-3 rounded-xl border border-zinc-800 bg-zinc-950 text-zinc-300 outline-none text-sm font-bold">
                        <option class="bg-zinc-900">Semua Status</option>
                        <option class="bg-zinc-900 text-emerald-400">Success</option>
                        <option class="bg-zinc-900 text-amber-400">Pending</option>
                        <option class="bg-zinc-900 text-rose-400">Expired</option>
                    </select>
                    <select
                        class="px-5 py-3 rounded-xl border border-zinc-800 bg-zinc-950 text-zinc-300 outline-none text-sm font-bold">
                        <option class="bg-zinc-900">Bulan Ini</option>
                        <option class="bg-zinc-900">Bulan Lalu</option>
                        <option class="bg-zinc-900">Tahun 2024</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-zinc-950/50 text-zinc-500 uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Order ID</th>
                            <th class="px-8 py-4">Detail Pembeli</th>
                            <th class="px-8 py-4">Event</th>
                            <th class="px-8 py-4">Tgl Transaksi</th>
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4 text-right">Total Tagihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800 border-t border-zinc-800">
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="px-8 py-6">
                                <span
                                    class="font-mono font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-3 py-1 rounded-lg text-sm">#TRX-99210</span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-zinc-200">Donni Prabowo</p>
                                <p class="text-xs text-zinc-500">donni@example.com</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-medium text-zinc-400">Jazz Night 2024</p>
                            </td>
                            <td class="px-8 py-6 text-sm text-zinc-500">
                                26 Mar 2024, 17:45
                            </td>
                            <td class="px-8 py-6">
                                <span
                                    class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-bold uppercase ring-1 ring-emerald-500/30">Success</span>
                            </td>
                            <td class="px-8 py-6 text-right font-black text-white">
                                Rp 155.000
                            </td>
                        </tr>
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="px-8 py-6">
                                <span
                                    class="font-mono font-bold text-zinc-400 bg-zinc-800 border border-zinc-700 px-3 py-1 rounded-lg text-sm">#TRX-99209</span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-zinc-200">Maya Sari</p>
                                <p class="text-xs text-zinc-500">maya@example.com</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-medium text-zinc-400">AI & Future Workshop</p>
                            </td>
                            <td class="px-8 py-6 text-sm text-zinc-500">
                                26 Mar 2024, 15:20
                            </td>
                            <td class="px-8 py-6">
                                <span
                                    class="px-3 py-1 bg-amber-500/10 text-amber-400 rounded-lg text-xs font-bold uppercase ring-1 ring-amber-500/30">Pending</span>
                            </td>
                            <td class="px-8 py-6 text-right font-black text-white">
                                Rp 55.000
                            </td>
                        </tr>
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="px-8 py-6">
                                <span
                                    class="font-mono font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-3 py-1 rounded-lg text-sm">#TRX-99208</span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-zinc-200">Budi Santoso</p>
                                <p class="text-xs text-zinc-500">budi@example.com</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-medium text-zinc-400">Hackathon 2024</p>
                            </td>
                            <td class="px-8 py-6 text-sm text-zinc-500">
                                25 Mar 2024, 10:00
                            </td>
                            <td class="px-8 py-6">
                                <span
                                    class="px-3 py-1 bg-zinc-800 text-zinc-400 rounded-lg text-xs font-bold uppercase ring-1 ring-zinc-700">Free</span>
                            </td>
                            <td class="px-8 py-6 text-right font-black text-white">
                                Rp 0
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-6 bg-zinc-950/50 border-t border-zinc-800 flex justify-between items-center">
                <p class="text-sm text-zinc-500 font-medium">Menampilkan 3 dari 124 transaksi</p>
                <div class="flex gap-2">
                    <button
                        class="px-4 py-2 border border-zinc-700 text-zinc-500 rounded-xl transition text-sm font-bold opacity-50 cursor-not-allowed">Previous</button>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow-md text-sm font-bold border border-indigo-500">1</button>
                    <button class="px-4 py-2 border border-zinc-700 text-zinc-400 rounded-xl hover:bg-zinc-800 transition text-sm font-bold">2</button>
                    <button
                        class="px-4 py-2 border border-zinc-700 text-zinc-400 rounded-xl hover:bg-zinc-800 transition text-sm font-bold">Next</button>
                </div>
            </div>
        </div>
@endsection