<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // 1. Tampilkan Semua Kategori (Public)
    public function index()
    {
        $categories = Category::with('items')->latest()->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar kategori berhasil diambil',
            'data'    => $categories
        ]);
    }

    // 2. Tambah Kategori Baru (Admin Only)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
            'deskripsi'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $category = Category::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data'    => $category
        ], 201);
    }

    // 3. Tampilkan Detail Kategori (Public)
    public function show($id)
    {
        $category = Category::with('items')->find($id);

        if (!$category) {
            return response()->json([
                'status'  => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail kategori berhasil diambil',
            'data'    => $category
        ]);
    }

    // 4. Update Kategori (Admin Only)
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status'  => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $id,
            'deskripsi'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Kategori berhasil diperbarui',
            'data'    => $category
        ]);
    }

    // 5. Hapus Kategori (Admin Only)
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status'  => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}