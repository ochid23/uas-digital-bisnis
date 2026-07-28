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
            <label class="block text-sm font-bold text-zinc-300 mb-2 uppercase tracking-wide">Poster Event (Dinamis File / Link URL)</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <span class="text-xs font-bold text-zinc-400 block mb-1">Opsi A: Upload File dari Perangkat</span>
                    <input type="file" name="poster" id="poster_file_input" accept="image/*" onchange="previewPosterFile(this)"
                        class="w-full px-4 py-3 bg-zinc-950 border-2 border-zinc-800 text-zinc-400 rounded-2xl focus:ring-1 focus:ring-indigo-500 outline-none text-xs file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-500/10 file:text-indigo-400 cursor-pointer">
                </div>
                <div>
                    <span class="text-xs font-bold text-zinc-400 block mb-1">Opsi B: Tempel Link URL Gambar Online</span>
                    <input type="url" name="poster_url" id="poster_url_input" value="{{ old('poster_url') }}" placeholder="https://images.unsplash.com/..." oninput="previewPosterUrl(this.value)"
                        class="w-full px-4 py-3.5 bg-zinc-950 border-2 border-zinc-800 text-white rounded-2xl focus:ring-1 focus:ring-indigo-500 outline-none text-xs placeholder-zinc-600 font-medium">
                </div>
            </div>
            
            <!-- Live Preview Container -->
            <div id="poster_preview_box" class="hidden mt-3 p-3 bg-zinc-950 border border-zinc-800 rounded-2xl flex items-center gap-4">
                <img id="poster_preview_img" src="" class="w-16 h-20 rounded-xl object-cover border border-zinc-700 shadow-md">
                <div>
                    <p class="text-xs font-bold text-emerald-400">✓ Pratinjau Gambar Berhasil Dimuat</p>
                    <p class="text-[11px] text-zinc-500">Poster ini akan ditampilkan secara dinamis di seluruh halaman acara.</p>
                </div>
            </div>

            @error('poster') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            @error('poster_url') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <script>
            function previewPosterFile(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('poster_preview_img').src = e.target.result;
                        document.getElementById('poster_preview_box').classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function previewPosterUrl(url) {
                if (url && url.trim().length > 5) {
                    document.getElementById('poster_preview_img').src = url;
                    document.getElementById('poster_preview_box').classList.remove('hidden');
                } else {
                    document.getElementById('poster_preview_box').classList.add('hidden');
                }
            }
        </script>

        <div class="pt-6 flex justify-end gap-4 border-t border-zinc-800">
            <a href="{{ route('admin.events.index') }}" class="px-6 py-4 text-zinc-400 font-bold hover:text-white hover:bg-zinc-800 rounded-2xl transition">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition">Simpan Event</button>
        </div>
    </form>
</div>
@endsection