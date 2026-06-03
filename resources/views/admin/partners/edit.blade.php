@extends('layouts.admin')

@section('page_title', 'Edit Partner')
@section('page_subtitle', 'Perbarui data partner pendukung')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-800">Form Perubahan Data Partner</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" value="{{ $partner->name }}" required>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Logo URL</label>
                    <input type="url" name="logo_url" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" value="{{ $partner->logo_url }}" required>
                    
                    {{-- Preview Box Logo Lama --}}
                    <div class="mt-4 p-4 border border-slate-100 rounded-xl bg-slate-50 inline-block">
                        <p class="text-[10px] font-bold text-slate-400 mb-2 uppercase tracking-widest">Preview Logo Saat Ini</p>
                        <img src="{{ $partner->logo_url }}" alt="Preview Logo" class="h-16 object-contain rounded mix-blend-multiply">
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-slate-100 flex items-center gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Update Partner
                    </button>
                    <a href="{{ route('admin.partners.index') }}" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection