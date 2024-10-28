<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagerController;
use App\Http\Controllers\MailServerController;
use App\Http\Controllers\EmailReceivedController;
use App\Http\Controllers\EmailContentController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth
Route::prefix('/')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('auth.login');
    Route::post('/', [AuthController::class, 'login'])->name('auth.login-post');
});


// middleware auth
Route::middleware(['auth'])->group(function () {

    // Action Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // middleware only admin
    Route::middleware(['admin'])->group(function () {

        // prefix user manager
        Route::prefix('user-manager')->group(function () {
            Route::get('/', [UserManagerController::class, 'index'])->name('user-manager');
            Route::post('/', [UserManagerController::class, 'store'])->name('user-manager-store');
            Route::post('/{id}', [UserManagerController::class, 'update'])->name('user-manager-update');
            Route::post('update-password/{id}', [UserManagerController::class, 'updatePassword'])->name('user-manager-update-password');
            Route::post('delete/{id}', [UserManagerController::class, 'destroy'])->name('user-manager-delete');
        });

    });

    // middleware client
    Route::middleware(['karyawan'])->group(function () {

        // Mail Server
        Route::prefix('mail-setting')->group(function () {
            Route::get('/', [MailServerController::class, 'index'])->name('mail-setting');
            Route::post('/{id}', [MailServerController::class, 'update'])->name('mail-setting-update');
        });

        // Mail Receive
        Route::prefix('email-received')->group(function () {
            Route::get('/', [EmailReceivedController::class, 'index'])->name('email-received');
        });

         // Mail Receive
         Route::prefix('auto-reply')->group(function () {
            Route::get('/', [EmailContentController::class, 'index'])->name('auto-reply');
            Route::post('/{id}', [EmailContentController::class, 'update'])->name('update-email-settings');
        });
    });
});
