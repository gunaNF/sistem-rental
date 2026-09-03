<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Rental;
use App\Models\RentalItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
    // 1. Buat Peminjaman / Transaksi Sewa Baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_mulai_sewa'   => 'required|date|after_or_equal:today',
            'tgl_selesai_sewa' => 'required|date|after_or_equal:tgl_mulai_sewa',
            'items'            => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:items,id',
            'items.*.jumlah'   => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Hitung total durasi hari sewa
        $tglMulai   = Carbon::parse($request->tgl_mulai_sewa);
        $tglSelesai = Carbon::parse($request->tgl_selesai_sewa);
        $durasiHari = $tglMulai->diffInDays($tglSelesai) ?: 1;

        DB::beginTransaction();
        try {
            $totalHargaTransaksi = 0;
            $itemsToInsert = [];

            // Pengecekan stok & hitung harga untuk tiap item
            foreach ($request->items as $itemData) {
                $item = Item::lockForUpdate()->find($itemData['id_barang']);

                if ($item->stok < $itemData['jumlah']) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => false,
                        'message' => "Stok untuk barang '{$item->nama_barang}' tidak mencukupi. Stok sisa: {$item->stok}"
                    ], 400);
                }

                $subtotal = $item->harga_per_hari * $itemData['jumlah'] * $durasiHari;
                $totalHargaTransaksi += $subtotal;

                // Kurangi stok barang
                $item->stok -= $itemData['jumlah'];
                $item->save();

                $itemsToInsert[] = [
                    'id_barang' => $item->id,
                    'jumlah'    => $itemData['jumlah'],
                    'subtotal'  => $subtotal,
                ];
            }

            // Buat header transaksi
            $rental = Rental::create([
                'id_pengguna'      => $request->user()->id,
                'kode_transaksi'   => 'RENT-' . strtoupper(uniqid()),
                'tgl_mulai_sewa'   => $request->tgl_mulai_sewa,
                'tgl_selesai_sewa' => $request->tgl_selesai_sewa,
                'total_harga'      => $totalHargaTransaksi,
                'status_transaksi' => 'menunggu',
            ]);

            // Simpan detail item
            foreach ($itemsToInsert as $detail) {
                RentalItem::create([
                    'id_transaksi' => $rental->id,
                    'id_barang'    => $detail['id_barang'],
                    'jumlah'       => $detail['jumlah'],
                    'subtotal'     => $detail['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi penyewaan berhasil dibuat',
                'data'    => $rental->load('rentalItems.item')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    // 2. Daftar Transaksi (Admin: Semua Transaksi, Customer: Milik Sendiri)
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Rental::with(['user', 'rentalItems.item', 'payment']);

        if ($user->peran !== 'admin') {
            $query->where('id_pengguna', $user->id);
        }

        $rentals = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar transaksi berhasil diambil',
            'data'    => $rentals
        ]);
    }

    // 3. Detail 1 Transaksi
    public function show(Request $request, $id)
    {
        $rental = Rental::with(['user', 'rentalItems.item', 'payment'])->find($id);

        if (!$rental) {
            return response()->json([
                'status'  => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if ($request->user()->peran !== 'admin' && $rental->id_pengguna !== $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'data'   => $rental
        ]);
    }

    // 4. Update Status Transaksi (Admin Only)
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_transaksi' => 'required|in:menunggu,disewa,selesai,dibatalkan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $rental = Rental::with('rentalItems')->find($id);

        if (!$rental) {
            return response()->json([
                'status'  => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $statusLama = $rental->status_transaksi;
        $statusBaru = $request->status_transaksi;

        DB::beginTransaction();
        try {
            // Jika status diubah ke 'selesai' atau 'dibatalkan', kembalikan stok barang
            if (in_array($statusBaru, ['selesai', 'dibatalkan']) && !in_array($statusLama, ['selesai', 'dibatalkan'])) {
                foreach ($rental->rentalItems as $detail) {
                    $item = Item::find($detail->id_barang);
                    if ($item) {
                        $item->stok += $detail->jumlah;
                        $item->save();
                    }
                }
            }

            $rental->status_transaksi = $statusBaru;
            $rental->save();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => "Status transaksi berhasil diubah menjadi '{$statusBaru}'",
                'data'    => $rental
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}