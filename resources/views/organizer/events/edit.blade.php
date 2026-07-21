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
                    <label for="poster_path" class="block text-sm font-bold text-zinc-300 mb-2">Poster Event <span class="text-zinc-500 font-normal">(Kosongkan jika tidak diubah)</span></label>
                    @if($event->poster_path)
                        <div class="mb-4 p-2 bg-zinc-950 border border-zinc-800 rounded-2xl inline-block">
                            <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster Saat Ini" class="h-40 object-cover rounded-xl shadow-md">
                        </div>
                    @endif
                    <input type="file" name="poster_path" id="poster_path" 
                        class="block w-full text-sm text-zinc-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all border border-zinc-800 bg-zinc-950 rounded-xl p-2 cursor-pointer">
                    @error('poster_path') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

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