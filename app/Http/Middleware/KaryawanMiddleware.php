<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KaryawanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {

            if (Auth::user()->role->name == "Karyawan" || Auth::user()->role->name == "Admin") {
                return $next($request);
            }

            Session::flush();
            Auth::logout();

            return redirect()->route('auth.login')->with('error', 'Anda bukan Karyawan');

        } else {

            if (Auth::user()->role->name == "Karyawan" || Auth::user()->role->name == "Admin") {
                return $next($request);
            }

            return redirect()->route('auth.login')->with('error', 'Anda bukan Karyawan');
        }
    }
}
