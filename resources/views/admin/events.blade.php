@extends('layouts.admin')

@section('content')

        <div class="bg-zinc-900 rounded-[2.5rem] border border-zinc-800 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-zinc-950/50 border-b border-zinc-800 flex gap-4">
                <input type="text" placeholder="Cari nama event..."
                    class="flex-1 px-5 py-3 rounded-xl border border-zinc-800 bg-zinc-950 text-zinc-100 placeholder:text-zinc-600 focus:ring-1 focus:ring-indigo-500 outline-none transition">
                <select class="px-5 py-3 rounded-xl border border-zinc-800 bg-zinc-950 text-zinc-300 outline-none">
                    <option class="bg-zinc-900">Semua Kategori</option>
                    <option class="bg-zinc-900">Musik</option>
                    <option class="bg-zinc-900">Workshop</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-zinc-950/50 text-zinc-500 uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-4 w-16">No</th>
                            <th class="px-8 py-4">Poster</th>
                            <th class="px-8 py-4">Event</th>
                            <th class="px-8 py-4">Harga / Stok</th>
                            <th class="px-8 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800 border-t border-zinc-800">
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="px-8 py-6 font-bold text-zinc-500">1</td>
                            <td class="px-8 py-6">
                                <img src="{{url('assets/concert.png')}}" class="w-16 h-20 rounded-xl object-cover shadow-sm border border-zinc-700">
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-black text-zinc-200">Jazz Night 2024</p>
                                <p class="text-xs text-zinc-500">Musik • 16 Nov 2024</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-indigo-400">Rp 150.000</p>
                                <p class="text-xs text-zinc-500">Stok: 42/100</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex gap-2">
                                    <button
                                        class="p-2.5 bg-zinc-800 border border-zinc-700 text-indigo-400 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button
                                        class="p-2.5 bg-zinc-800 border border-zinc-700 text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="px-8 py-6 font-bold text-zinc-500">2</td>
                            <td class="px-8 py-6">
                                <img src="{{url('assets/workshop.png')}}" class="w-16 h-20 rounded-xl object-cover shadow-sm border border-zinc-700">
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-black text-zinc-200">AI & Future Workshop</p>
                                <p class="text-xs text-zinc-500">Tech • 26 Oct 2024</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-indigo-400">Rp 50.000</p>
                                <p class="text-xs text-zinc-500">Stok: 12/50</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex gap-2">
                                    <button
                                        class="p-2.5 bg-zinc-800 border border-zinc-700 text-indigo-400 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button
                                        class="p-2.5 bg-zinc-800 border border-zinc-700 text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

@endsection