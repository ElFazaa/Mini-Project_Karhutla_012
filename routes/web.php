<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeninjauanController;
use App\Http\Controllers\PenerusanInformasiController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\AkunController;
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

Route::get('/rekap', [RekapController::class, 'index'])
    ->middleware(['auth', 'role:pemantau,koordinator'])
    ->name('rekap.index');

Route::middleware(['auth', 'role:pelapor'])->group(function () {
    Route::get('/wilayah/{id}/ringkasan', [LaporanController::class, 'ringkasanWilayah'])
        ->whereNumber('id')->name('wilayah.ringkasan');

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

    Route::get('/laporan/{id}/edit', [LaporanController::class, 'edit'])
        ->whereNumber('id')
        ->name('laporan.edit');

    Route::put('/laporan/{id}', [LaporanController::class, 'update'])
        ->whereNumber('id')
        ->name('laporan.update');

    Route::post('/laporan', [LaporanController::class, 'store'])
        ->name('laporan.store');
});

Route::middleware(['auth', 'role:pemantau'])->group(function () {
    Route::get('/pemantau', [PeninjauanController::class, 'index'])
        ->name('pemantau.index');

    Route::get('/pemantau/laporan/{id}', [PeninjauanController::class, 'show'])
        ->whereNumber('id')->name('pemantau.show');

    Route::get('/pemantau/laporan/{id}/bukti', [PeninjauanController::class, 'bukti'])
        ->whereNumber('id')->name('pemantau.bukti');

    Route::post('/pemantau/laporan/{id}/peninjauan', [PeninjauanController::class, 'store'])
        ->whereNumber('id')->name('pemantau.peninjauan.store');
});

Route::middleware(['auth', 'role:koordinator'])->group(function () {
    Route::get('/koordinator/akun', [AkunController::class, 'index'])
        ->name('akun.index');
    Route::get('/koordinator/akun/buat', [AkunController::class, 'create'])
        ->name('akun.create');
    Route::post('/koordinator/akun', [AkunController::class, 'store'])
        ->name('akun.store');
    Route::get('/koordinator/akun/{id}/edit', [AkunController::class, 'edit'])
        ->whereNumber('id')->name('akun.edit');
    Route::put('/koordinator/akun/{id}', [AkunController::class, 'update'])
        ->whereNumber('id')->name('akun.update');

    Route::get('/koordinator/wilayah', [WilayahController::class, 'index'])
        ->name('wilayah.index');
    Route::get('/koordinator/wilayah/buat', [WilayahController::class, 'create'])
        ->name('wilayah.create');
    Route::post('/koordinator/wilayah', [WilayahController::class, 'store'])
        ->name('wilayah.store');
    Route::get('/koordinator/wilayah/{id}/edit', [WilayahController::class, 'edit'])
        ->whereNumber('id')->name('wilayah.edit');
    Route::put('/koordinator/wilayah/{id}', [WilayahController::class, 'update'])
        ->whereNumber('id')->name('wilayah.update');
    Route::delete('/koordinator/wilayah/{id}', [WilayahController::class, 'destroy'])
        ->whereNumber('id')->name('wilayah.destroy');

    Route::get('/koordinator', [PenerusanInformasiController::class, 'index'])
        ->name('koordinator.index');

    Route::get('/koordinator/laporan/{id}', [PenerusanInformasiController::class, 'show'])
        ->whereNumber('id')->name('koordinator.show');

    Route::get('/koordinator/laporan/{id}/bukti', [PenerusanInformasiController::class, 'bukti'])
        ->whereNumber('id')->name('koordinator.bukti');

    Route::post('/koordinator/laporan/{id}/penerusan', [PenerusanInformasiController::class, 'store'])
        ->whereNumber('id')->name('koordinator.penerusan.store');

    Route::post('/koordinator/laporan/{id}/tutup', [PenerusanInformasiController::class, 'tutup'])
        ->whereNumber('id')->name('koordinator.tutup');
});
