<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlatCamping; // Sesuaikan dengan nama Model produk kamu
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Tugas 1: Menampilkan semua daftar alat camping (Katalog Public)
     */
    public function index(Request $request)
    {
        try {
            $query = AlatCamping::query();

            // Filter opsional jika dari Vue memilih kategori tertentu
            if ($request->has('kategori') && $request->kategori != '') {
                $query->where('kategori', $request->kategori);
            }

            $produk = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar katalog produk berhasil diambil',
                'data'    => $produk
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tugas 2: Menampilkan detail 1 alat camping berdasarkan ID (Public)
     */
    public function show($id)
    {
        try {
            $produk = AlatCamping::find($id);

            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data produk tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail produk berhasil diambil',
                'data'    => $produk
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail produk',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}