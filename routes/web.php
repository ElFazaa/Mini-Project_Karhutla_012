<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::get('/pelapor', function () {
    return 'Halaman Pelapor';
})->middleware('role:pelapor')->name('pelapor.index');

Route::get('/pemantau', function () {
    return 'Halaman Pemantau';
})->middleware('role:pemantau')->name('pemantau.index');

Route::get('/koordinator', function () {
    return 'Halaman Koordinator';
})->middleware('role:koordinator')->name('koordinator.index');