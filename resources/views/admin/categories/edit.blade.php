@extends('layouts.admin')

@section('page_title', 'Edit Kategori')
@section('page_subtitle', 'Ubah rincian nama kategori')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-800">Form Mengedit Nama Kategori</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm" value="{{ $category->name }}" required>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Update Nama Kategori
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection