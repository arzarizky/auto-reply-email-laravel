<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Role;


class AuthController extends Controller
{
    public function index() {
        return view('pages.auth.login.index');
    }

    public function login(LoginRequest $request) {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            if (Auth::user()->isAdmin()) {
                return redirect()->route('auto-reply')->with('success', 'Sukses login sesi telah dibuat');
            } elseif (Auth::user()->isKaryawan()) {
                return redirect()->route('auto-reply')->with('success', 'Sukses login sesi telah dibuat');
            } else {
                return redirect()->route('auth.login')->with('error', 'Login Gagal');
            }
        }

        return redirect()->route('auth.login')->with('error', 'Email atau password yang anda masukan salah');
    }

    public function logout() {
        if (Auth::check() == true) {

            Session::flush();
            Auth::logout();
            return redirect()->route('auth.login')->with('success', 'Sukses logout sesi telah dihapus');
        } else {

            Session::flush();
            Auth::logout();
            return redirect()->route('auth.login')->with('error', 'Silahkan login terlebih dahulu untuk logout');
        }
    }
}
