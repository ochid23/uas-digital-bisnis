<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- WAJIB DITAMBAHKAN UNTUK BIKIN SLUG

class CategoryController extends Controller
{
    // READ & SEARCH: Menampilkan data dan fitur pencarian
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    // CREATE: Menyimpan data ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // <-- MENAMBAHKAN SLUG OTOMATIS
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // UPDATE: Menampilkan form edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // UPDATE: Menyimpan perubahan data
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // <-- MENAMBAHKAN SLUG OTOMATIS SAAT EDIT
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // DELETE: Menghapus data
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}