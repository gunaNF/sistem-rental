<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            $payments = Payment::with(['rental.user'])->latest()->get();

            return response()->json([
                'status' => 'success',
                'data' => $payments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat data pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // Mendukung key 'status' atau 'status_bayar' dari frontend
        $statusBaru = $request->input('status') ?? $request->input('status_bayar');

        if (!in_array($statusBaru, ['pending', 'diverifikasi', 'ditolak', 'belum_dibayar'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Status yang diberikan tidak valid.'
            ], 422);
        }

        try {
            $payment = Payment::findOrFail($id);
            
            // Simpan ke kolom status_bayar (atau sesuaikan dengan nama kolom di database kamu)
            $payment->status_bayar = $statusBaru;
            $payment->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status pembayaran berhasil diperbarui.',
                'data' => $payment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}