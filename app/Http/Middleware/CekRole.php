<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan pengguna sudah terautentikasi
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Periksa apakah pengguna memiliki peran yang diizinkan
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }
        
        // Jika tidak, alihkan mereka ke rute error atau rute yang sesuai
        return redirect()->route('error');
    }
}
