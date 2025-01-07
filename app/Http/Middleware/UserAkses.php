<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Jika pengguna adalah superadmin, izinkan akses tanpa batasan
        if (auth()->user()->role == 'superadmin') {
            return $next($request);
        }

        // Jika pengguna memiliki peran yang diizinkan, lanjutkan
        if (auth()->user()->role == $role) {
            return $next($request);
        }

        // Jika pengguna tidak memiliki akses, tampilkan pesan error
        return response()->json(['Anda tidak di perbolehkan Akses Halaman ini'], 403);
    }
}
