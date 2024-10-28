<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() == true) {

            if (Auth::user()->role->name == "Admin") {
                return $next($request);
            }

            Session::flush();
            Auth::logout();

            return redirect()->route('auth.login')->with('error', 'Anda bukan admin');
        } else {

            if (Auth::user()->role->name == "Admin") {
                return $next($request);
            }

            return redirect()->route('auth.login')->with('error', 'Anda bukan admin');
        }
    }
}
