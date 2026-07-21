@extends('layouts.admin')

@section('page_title', 'Edit Kategori')
@section('page_subtitle', 'Ubah rincian nama kategori')

@section('content')
<div class="max-w-xl">
    <div class="bg-zinc-900 rounded-[2rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
        <div class="px-8 py-5 border-b border-zinc-800 bg-zinc-950/50">
            <h3 class="font-black text-white">Form Mengedit Nama Kategori</h3>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-bold text-zinc-300 mb-2">Nama Kategori</label>
                    <input type="text" name="name" 
                        class="w-full px-4 py-3 bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm" 
                        value="{{ $category->name }}" required>
                </div>

                <div class="pt-6 border-t border-zinc-800 flex items-center gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-500/20 text-sm">
                        Update Nama Kategori
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="bg-zinc-800 text-zinc-300 px-6 py-3 rounded-xl font-bold hover:bg-zinc-700 hover:text-white transition-all text-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection