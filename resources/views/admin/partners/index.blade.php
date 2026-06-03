@extends('layouts.admin')

@section('page_title', 'Manajemen Partner')
@section('page_subtitle', 'Kelola daftar partner pendukung AmikomEventHub')

@section('content')
<div class="space-y-8">

    {{-- Form Tambah Partner Baru --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-800">Tambah Partner Baru</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.partners.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" placeholder="Masukkan nama perusahaan/partner" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Logo URL</label>
                        {{-- Bagian ini sudah diubah dari placeholder menjadi value --}}
                        <input type="url" name="logo_url" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" value="https://placehold.co/200x200" required>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Simpan Partner
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Partner --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-slate-50">
            <h3 class="font-bold text-slate-800">Daftar Partner Saat Ini</h3>
            
            {{-- Form Input Search Komponen Berbentuk Kolom Pencarian (Soal 3) --}}
            <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama partner..." class="px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm w-full sm:w-64 bg-white">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-900 transition">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.partners.index') }}" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition flex items-center">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Logo</th>
                        <th class="px-6 py-4">Nama Partner</th>
                        <th class="px-6 py-4">Ditambahkan Pada</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($partners as $partner)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $partner->id }}</td>
                        <td class="px-6 py-4">
                            <div class="w-16 h-16 bg-white border border-slate-100 rounded-xl flex items-center justify-center p-2 shadow-sm">
                                <img src="{{ $partner->logo_url }}" alt="Logo {{ $partner->name }}" class="max-h-full object-contain">
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $partner->name }}</td>
                        <td class="px-6 py-4">{{ $partner->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.partners.edit', $partner->id) }}" class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg font-bold text-xs hover:bg-amber-200 transition">Edit</a>
                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus partner ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg font-bold text-xs hover:bg-red-200 transition">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-medium">Belum ada data partner yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection