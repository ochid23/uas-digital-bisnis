@extends('layouts.admin')

@section('page_title', 'Edit Partner')
@section('page_subtitle', 'Perbarui data partner pendukung')

@section('content')
<div class="max-w-3xl">
    <div class="bg-zinc-900 rounded-[2rem] border border-zinc-800 shadow-lg shadow-black/20 overflow-hidden">
        <div class="px-8 py-5 border-b border-zinc-800 bg-zinc-950/50">
            <h3 class="font-black text-white">Form Perubahan Data Partner</h3>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-bold text-zinc-300 mb-2">Nama Partner</label>
                    <input type="text" name="name" 
                        class="w-full px-4 py-3 bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm" 
                        value="{{ $partner->name }}" required>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-zinc-300 mb-2">Logo URL</label>
                    <input type="url" name="logo_url" 
                        class="w-full px-4 py-3 bg-zinc-950 border border-zinc-800 text-white rounded-xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm" 
                        value="{{ $partner->logo_url }}" required>
                    
                    {{-- Preview Box Logo Lama --}}
                    <div class="mt-4 p-4 border border-zinc-800 rounded-2xl bg-zinc-950 inline-block">
                        <p class="text-[10px] font-bold text-zinc-500 mb-3 uppercase tracking-widest">Preview Logo Saat Ini</p>
                        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-3 flex items-center justify-center">
                            <img src="{{ $partner->logo_url }}" alt="Preview Logo" class="h-16 object-contain rounded-lg">
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-zinc-800 flex items-center gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-500/20 text-sm">
                        Update Partner
                    </button>
                    <a href="{{ route('admin.partners.index') }}" class="bg-zinc-800 text-zinc-300 px-6 py-3 rounded-xl font-bold hover:bg-zinc-700 hover:text-white transition-all text-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection