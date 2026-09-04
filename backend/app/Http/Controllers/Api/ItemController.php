<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // 1. Tampilkan Semua Barang (Public)
    public function index()
    {
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
            'id_kategori'    => 'required|exists:categories,id',
            'harga_per_hari' => 'required|numeric',
            'stok'           => 'required|integer',
            'deskripsi'      => 'nullable|string',
            'foto_barang'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi foto
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->except('foto_barang');

        // Handle upload foto_barang
        if ($request->hasFile('foto_barang')) {
            $path = $request->file('foto_barang')->store('items', 'public');
            $data['foto_barang'] = $path; // Menyimpan path "items/filename.jpg"
        }

        $item = Item::create($data);
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
            'foto_barang'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->except('foto_barang');

        // Handle update foto_barang
        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama jika ada di storage
            if ($item->foto_barang && Storage::disk('public')->exists($item->foto_barang)) {
                Storage::disk('public')->delete($item->foto_barang);
            }

            // Simpan foto baru
            $path = $request->file('foto_barang')->store('items', 'public');
            $data['foto_barang'] = $path;
        }

        $item->update($data);
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

        // Hapus foto dari storage jika ada
        if ($item->foto_barang && Storage::disk('public')->exists($item->foto_barang)) {
            Storage::disk('public')->delete($item->foto_barang);
        }

        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}