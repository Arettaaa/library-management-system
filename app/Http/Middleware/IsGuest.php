<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Jika pengguna sudah terautentikasi, alihkan mereka ke rute dashboard
            return redirect()->route('dashboarduser')->with('notAllowed', 'You have logged in!');
        }

        // Jika pengguna belum terautentikasi, lanjutkan eksekusi ke middleware atau controller berikutnya
        return $next($request);
    }
}
