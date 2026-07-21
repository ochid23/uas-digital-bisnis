@extends('layouts.admin')

@section('page_title', 'Manajemen Partner')
@section('page_subtitle', 'Kelola daftar partner pendukung naazhi.')

@section('content')
<div class="space-y-8">

    {{-- Form Tambah Partner Baru --}}
    <div class="bg-zinc-900 rounded-[2rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
        <div class="px-8 py-5 border-b border-zinc-800 bg-zinc-950/50">
            <h3 class="font-black text-white">Tambah Partner Baru</h3>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.partners.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-zinc-300 mb-2">Nama Partner</label>
                        <input type="text" name="name" 
                            class="w-full px-4 py-3 bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm placeholder-zinc-600" 
                            placeholder="Masukkan nama perusahaan/partner" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-zinc-300 mb-2">Logo URL</label>
                        <input type="url" name="logo_url" 
                            class="w-full px-4 py-3 bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm" 
                            value="https://placehold.co/200x200" required>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-500/20 text-sm">
                        Simpan Partner
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Partner --}}
    <div class="bg-zinc-900 rounded-[2rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
        <div class="px-8 py-5 border-b border-zinc-800 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-zinc-950/50">
            <h3 class="font-black text-white">Daftar Partner Saat Ini</h3>
            
            {{-- Form Input Search --}}
            <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama partner..." 
                    class="px-4 py-2.5 bg-zinc-950 border border-zinc-800 text-white rounded-xl outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-full sm:w-64 placeholder-zinc-600">
                <button type="submit" class="bg-zinc-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-zinc-700 transition-all">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.partners.index') }}" class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-rose-500/20 transition-all flex items-center">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-zinc-400 border-collapse">
                <thead class="bg-zinc-950/50 text-zinc-500 text-[10px] uppercase font-black tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-8 py-5 w-24">ID</th>
                        <th class="px-6 py-5">Logo</th>
                        <th class="px-6 py-5">Nama Partner</th>
                        <th class="px-6 py-5">Ditambahkan Pada</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    @forelse($partners as $partner)
                    <tr class="hover:bg-zinc-800/30 transition-all duration-200">
                        <td class="px-8 py-6 font-bold text-zinc-500">#{{ $partner->id }}</td>
                        <td class="px-6 py-6">
                            <div class="w-16 h-16 bg-zinc-950 border border-zinc-800 rounded-xl flex items-center justify-center p-2 shadow-sm">
                                <img src="{{ $partner->logo_url }}" alt="Logo {{ $partner->name }}" class="max-h-full object-contain rounded-lg">
                            </div>
                        </td>
                        <td class="px-6 py-6 font-black text-white text-base">{{ $partner->name }}</td>
                        <td class="px-6 py-6 font-medium">{{ $partner->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.partners.edit', $partner->id) }}" 
                                    class="px-4 py-2 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-xl font-bold text-xs hover:bg-amber-500/20 transition-all">
                                    Edit
                                </a>
                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus partner ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="px-4 py-2 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-xl font-bold text-xs hover:bg-rose-500/20 transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="w-16 h-16 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-zinc-700/50">
                                <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <p class="text-zinc-500 font-medium">Belum ada data partner yang terdaftar atau ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection