<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 1. Register Customer Baru
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'kata_sandi' => 'required|string|min:8',
            'no_telepon' => 'nullable|string',
            'alamat'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'peran'      => 'customer',
            'no_telepon' => $request->no_telepon,
            'alamat'     => $request->alamat,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => true,
            'message'      => 'Registrasi berhasil',
            'data'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ], 201);
    }

    // 2. Login (Admin & Customer)
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'      => 'required|email',
            'kata_sandi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->kata_sandi, $user->kata_sandi)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau kata sandi salah'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => true,
            'message'      => 'Login berhasil',
            'data'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    // 3. Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Berhasil keluar (logout)'
        ]);
    }

    // 4. Get Data Profil Pengguna yang Sedang Login
    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'data'   => $request->user()
        ]);
    }

    // 5. Update Data Profil (Nama, Email, No HP, Alamat)
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Menerima input dari frontend (baik menggunakan variabel nama/name, no_telepon/no_hp)
        $namaInput = $request->nama ?? $request->name;
        $noHpInput = $request->no_telepon ?? $request->no_hp;

        $validator = Validator::make([
            'nama'       => $namaInput,
            'email'      => $request->email,
            'no_telepon' => $noHpInput,
            'alamat'     => $request->alamat,
        ], [
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string',
            'alamat'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user->update([
            'nama'       => $namaInput,
            'email'      => $request->email,
            'no_telepon' => $noHpInput,
            'alamat'     => $request->alamat,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $user
        ]);
    }

    // 6. Ubah Kata Sandi
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Pengecekan kata sandi lama pada kolom 'kata_sandi'
        if (!Hash::check($request->current_password, $user->kata_sandi)) {
            return response()->json([
                'status'  => false,
                'message' => 'Kata sandi saat ini tidak cocok'
            ], 400);
        }

        $user->update([
            'kata_sandi' => Hash::make($request->password)
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Kata sandi berhasil diubah'
        ]);
    }
}