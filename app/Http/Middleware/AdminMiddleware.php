<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Pastikan pengguna adalah admin
        $user = Auth::user();
        if ($user->role !== 'admin') {
            // Jika bukan admin, izinkan akses ke halaman, tetapi pembatasan logika dilakukan di view/controller
            return $next($request);
        }

        // Lanjutkan permintaan
        return $next($request);
    }
}
