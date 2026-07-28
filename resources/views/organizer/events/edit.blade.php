@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-white">Edit Event: <span class="text-indigo-400">{{ $event->title }}</span></h2>
        <p class="text-zinc-400 mt-1 text-sm">Perbarui informasi acara Anda di bawah ini.</p>
    </div>

    <div class="bg-zinc-900 shadow-xl shadow-black/20 border border-zinc-800 rounded-[2rem] p-8">
        <form action="{{ route('organizer.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-bold text-zinc-300 mb-2">Judul Event</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" 
                        class="block w-full rounded-xl bg-zinc-950 border border-zinc-800 text-white px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all sm:text-sm" required>
                    @error('title') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-bold text-zinc-300 mb-2">Kategori</label>
                    <select name="category_id" id="category_id" 
                        class="block w-full rounded-xl bg-zinc-950 border border-zinc-800 text-white px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all sm:text-sm" required>
                        <option value="" class="text-zinc-500">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Date & Time -->
                <div>
                    <label for="date" class="block text-sm font-bold text-zinc-300 mb-2">Tanggal & Waktu</label>
                    <input type="datetime-local" name="date" id="date" value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d\TH:i')) }}" 
                        class="block w-full rounded-xl bg-zinc-950 border border-zinc-800 text-white px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all sm:text-sm [color-scheme:dark]" required>
                    @error('date') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Location -->
                <div class="md:col-span-2">
                    <label for="location" class="block text-sm font-bold text-zinc-300 mb-2">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" 
                        class="block w-full rounded-xl bg-zinc-950 border border-zinc-800 text-white px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all sm:text-sm" required>
                    @error('location') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-bold text-zinc-300 mb-2">Harga Tiket (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $event->price) }}" min="0" 
                        class="block w-full rounded-xl bg-zinc-950 border border-zinc-800 text-white px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all sm:text-sm" required>
                    @error('price') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-bold text-zinc-300 mb-2">Stok Tiket</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $event->stock) }}" min="0" 
                        class="block w-full rounded-xl bg-zinc-950 border border-zinc-800 text-white px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all sm:text-sm" required>
                    @error('stock') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Poster Path -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-zinc-300 mb-2">Poster Event (File atau Link URL Gambar)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <span class="text-xs font-bold text-zinc-400 block mb-1">Opsi A: Upload File Baru</span>
                            <input type="file" name="poster_path" id="poster_file_input" accept="image/*" onchange="previewOrgPosterFile(this)" 
                                class="block w-full text-xs text-zinc-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all border border-zinc-800 bg-zinc-950 rounded-xl p-2 cursor-pointer">
                        </div>
                        <div>
                            <span class="text-xs font-bold text-zinc-400 block mb-1">Opsi B: Ganti Link URL Gambar Online</span>
                            <input type="url" name="poster_url" id="poster_url_input" value="{{ old('poster_url', str_starts_with($event->poster_path ?? '', 'http') ? $event->poster_path : '') }}" placeholder="https://images.unsplash.com/..." oninput="previewOrgPosterUrl(this.value)"
                                class="block w-full rounded-xl bg-zinc-950 border border-zinc-800 text-white px-4 py-2.5 focus:outline-none focus:border-indigo-500 text-xs placeholder-zinc-600 font-medium">
                        </div>
                    </div>

                    <div class="mt-3 p-3 bg-zinc-950 border border-zinc-800 rounded-2xl flex items-center gap-4">
                        <img id="org_poster_preview_img" src="{{ $event->poster_url }}" alt="Poster Saat Ini" class="w-16 h-20 object-cover rounded-xl shadow-md border border-zinc-700">
                        <div>
                            <p class="text-xs font-bold text-zinc-300 mb-1">Poster Saat Ini</p>
                            <p class="text-[11px] text-zinc-500">Pilih file baru atau ganti URL jika ingin memperbarui poster.</p>
                        </div>
                    </div>

                    @error('poster_path') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    @error('poster_url') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <script>
                    function previewOrgPosterFile(input) {
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('org_poster_preview_img').src = e.target.result;
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }

                    function previewOrgPosterUrl(url) {
                        if (url && url.trim().length > 5) {
                            document.getElementById('org_poster_preview_img').src = url;
                        }
                    }
                </script>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-bold text-zinc-300 mb-2">Deskripsi Event</label>
                    <textarea name="description" id="description" rows="5" 
                        class="block w-full rounded-xl bg-zinc-950 border border-zinc-800 text-white px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all sm:text-sm" required>{{ old('description', $event->description) }}</textarea>
                    @error('description') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-zinc-800">
                <a href="{{ route('organizer.events.index') }}" class="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-800 text-zinc-300 hover:bg-zinc-700 hover:text-white transition-all">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-sm bg-indigo-600 text-white shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition-all">Update Event</button>
            </div>
        </form>
    </div>
</div>
@endsection