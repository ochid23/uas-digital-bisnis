@extends('layouts.admin')
@section('title', 'Tambah Event Baru - naazhi.')
@section('page_title', 'Tambah Event Baru')
@section('page_subtitle', 'Masukkan detail acara baru yang akan diselenggarakan.')

@section('content')
<div class="bg-zinc-900 p-8 rounded-[2.5rem] border border-zinc-800 shadow-lg shadow-black/20 max-w-3xl">
    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Judul Event</label>
            <input type="text" name="title" value="{{ old('title') }}" 
                class="w-full px-5 py-4 bg-zinc-950 border-2 border-zinc-800 text-white rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium placeholder-zinc-600" 
                placeholder="Contoh: Konser Kemerdekaan" required>
            @error('title') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Kategori</label>
            <select name="category_id" 
                class="w-full px-5 py-4 bg-zinc-950 border-2 border-zinc-800 text-white rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium" required>
                <option value="" class="text-zinc-500">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Deskripsi</label>
            <textarea name="description" rows="4" 
                class="w-full px-5 py-4 bg-zinc-950 border-2 border-zinc-800 text-white rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium placeholder-zinc-600" 
                placeholder="Tuliskan deskripsi lengkap acara...">{{ old('description') }}</textarea>
            @error('description') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Tanggal & Waktu</label>
                <input type="datetime-local" name="date" value="{{ old('date') }}" 
                    class="w-full px-5 py-4 bg-zinc-950 border-2 border-zinc-800 text-white rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium [color-scheme:dark]" required>
                @error('date') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" 
                    class="w-full px-5 py-4 bg-zinc-950 border-2 border-zinc-800 text-white rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium placeholder-zinc-600" 
                    placeholder="Contoh: Gedung Serbaguna" required>
                @error('location') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', 0) }}" 
                    class="w-full px-5 py-4 bg-zinc-950 border-2 border-zinc-800 text-white rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium" required min="0">
                @error('price') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Kapasitas (Stok)</label>
                <input type="number" name="stock" value="{{ old('stock', 1) }}" 
                    class="w-full px-5 py-4 bg-zinc-950 border-2 border-zinc-800 text-white rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium" required min="1">
                @error('stock') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Poster Event (Opsional)</label>
            <input type="file" name="poster" accept="image/*" 
                class="w-full px-5 py-4 bg-zinc-950 border-2 border-zinc-800 text-zinc-400 rounded-2xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition font-medium file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 cursor-pointer">
            @error('poster') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="pt-6 flex justify-end gap-4 border-t border-zinc-800">
            <a href="{{ route('admin.events.index') }}" class="px-6 py-4 text-zinc-400 font-bold hover:text-white hover:bg-zinc-800 rounded-2xl transition">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition">Simpan Event</button>
        </div>
    </form>
</div>
@endsection