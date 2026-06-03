@extends('layouts.admin')

@section('page_title', 'Manajemen Kategori')
@section('page_subtitle', 'Kelola kelompok kategori event di AmikomEventHub')

@section('content')
<div class="space-y-8">

    {{-- Form Penambahan Kategori (Soal 1) --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden max-w-xl">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-800">Form Penambahan Kategori</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 sm:items-end">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: Konser Musik, Seminar" required>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition h-fit shadow-lg shadow-indigo-200">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Kategori & Fitur Pencarian (Soal 3) --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-slate-50">
            <h3 class="font-bold text-slate-800">Tabel Daftar Kategori</h3>
            
            {{-- Form Input Search Kolom Pencarian --}}
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..." class="px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-full sm:w-64 bg-white">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-900 transition">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition flex items-center">Reset</a>
                @endif
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Dibuat Pada</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $category->id }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $category->name }}</td>
                        <td class="px-6 py-4">{{ $category->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg font-bold text-xs hover:bg-amber-200 transition">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg font-bold text-xs hover:bg-red-200 transition">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400 font-medium">Kategori yang dicari tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection