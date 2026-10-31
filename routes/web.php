<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
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

Route::middleware(['auth', 'role:pelapor'])->group(function () {
    Route::get('/pelapor', function () {
        return redirect()->route('laporan.index');
    })->name('pelapor.index');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('/laporan/buat', [LaporanController::class, 'create'])
        ->name('laporan.create');

    Route::get('/laporan/{id}', [LaporanController::class, 'show'])
        ->whereNumber('id')
        ->name('laporan.show');

    Route::get('/laporan/{id}/bukti', [LaporanController::class, 'bukti'])
        ->whereNumber('id')
        ->name('laporan.bukti');

    Route::post('/laporan', [LaporanController::class, 'store'])
        ->name('laporan.store');
});

Route::get('/pemantau', function () {
    return 'Halaman Pemantau';
})->middleware('role:pemantau')->name('pemantau.index');

Route::get('/koordinator', function () {
    return 'Halaman Koordinator';
})->middleware('role:koordinator')->name('koordinator.index');
