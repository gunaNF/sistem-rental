<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    // 1. Tampilkan Semua Barang (Public)
    public function index()
    {
        // Mengambil semua barang beserta detail relasi kategorinya
        $items = Item::with('category')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar barang berhasil diambil',
            'data'    => $items
        ]);
    }

    // 2. Tambah Barang Baru (Admin)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang'    => 'required|string|max:255',
            'id_kategori'    => 'required|exists:categories,id', // Memastikan ID Kategori ada di tabel categories
            'harga_per_hari' => 'required|numeric',
            'stok'           => 'required|integer',
            'deskripsi'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $item = Item::create($request->all());

        // Load relasi kategori untuk response
        $item->load('category');

        return response()->json([
            'status'  => true,
            'message' => 'Barang berhasil ditambahkan',
            'data'    => $item
        ], 201);
    }

    // 3. Detail Barang Berdasarkan ID
    public function show($id)
    {
        // Ambil 1 barang beserta detail kategorinya
        $item = Item::with('category')->find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $item
        ]);
    }

    // 4. Update Barang (Admin)
    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_barang'    => 'sometimes|required|string|max:255',
            'id_kategori'    => 'sometimes|required|exists:categories,id',
            'harga_per_hari' => 'sometimes|required|numeric',
            'stok'           => 'sometimes|required|integer',
            'deskripsi'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $item->update($request->all());

        // Fresh load relasi kategori setelah update
        $item->load('category');

        return response()->json([
            'status'  => true,
            'message' => 'Data barang berhasil diperbarui',
            'data'    => $item
        ]);
    }

    // 5. Hapus Barang (Admin)
    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}