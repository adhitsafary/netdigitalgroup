<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role, $specificRoute = null)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login'); // Atau halaman lain jika user belum login
        }

        // Ambil role pengguna yang sedang login
        $user = Auth::user();

        // Cek apakah role user sesuai dengan role yang diizinkan
        if ($user->role !== $role) {
            return abort(403, 'Anda tidak memiliki akses untuk role ini.'); // Forbidden
        }

        // Jika ada route spesifik, cek apakah pengguna bisa mengakses route tersebut
        if ($specificRoute && !$request->is($specificRoute)) {
            return abort(403, 'Anda tidak memiliki akses ke route ini.');
        }

        return $next($request);
    }
}
