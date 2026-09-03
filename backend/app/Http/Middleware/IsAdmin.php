<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan kolom peran adalah 'admin'
        if ($request->user() && $request->user()->peran === 'admin') {
            return $next($request);
        }

        // Jika bukan admin, kembalikan response 403 Forbidden
        return response()->json([
            'status'  => false,
            'message' => 'Akses ditolak! Hanya Admin yang diizinkan mengelola barang.'
        ], 403);
    }
}