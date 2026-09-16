<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// --- PUBLIC ROUTES (Tanpa Login) ---
// ==========================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Katalog Kategori & Barang
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{id}', [ItemController::class, 'show']);

// Katalog Produk
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);


// ==========================================
// --- PROTECTED ROUTES (Wajib Bearer Token) ---
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. Profil & Autentikasi User
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // 2. Transaksi Penyewaan Customer
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::post('/rentals', [RentalController::class, 'store']);
    Route::get('/rentals/{id}', [RentalController::class, 'show']);

    // --------------------------------------
    // --- KHUSUS ROLE ADMIN ---
    // --------------------------------------
    Route::middleware(IsAdmin::class)->group(function () {
        // CRUD Kategori
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // CRUD Barang
        Route::post('/items', [ItemController::class, 'store']);
        Route::put('/items/{id}', [ItemController::class, 'update']);
        Route::delete('/items/{id}', [ItemController::class, 'destroy']);

        // Kelola Status Transaksi (Verifikasi/Update)
        Route::put('/rentals/{id}/status', [RentalController::class, 'updateStatus']);
    });
});