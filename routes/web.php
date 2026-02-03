<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; // <-- tambahkan ini
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\Admin\{
    JenisTesAdminController,
    SlotWaktuAdminController,
    FakultasAdminController,
    ProgramStudiAdminController,
    PemesananAdminController
    
};
use App\Http\Controllers\ProfileController;



/** DASHBOARD */
Route::middleware(['auth'])->group(function () {
    // Pakai controller agar data terkirim
    Route::get('/dasbor', [DashboardController::class, 'index'])->name('dasbor');

    // (Opsional) /dashboard diarahkan ke /dasbor biar konsisten
    Route::redirect('/dashboard', '/dasbor')->name('dashboard');

    // Pemesanan
    Route::get('/pemesanan',          [PemesananController::class, 'indeks'])->name('pemesanan.indeks');
    Route::get('/pemesanan/buat',     [PemesananController::class, 'buat'])->name('pemesanan.buat');
    Route::post('/pemesanan',         [PemesananController::class, 'simpan'])->name('pemesanan.simpan');
    Route::get('/pemesanan/{kode}',   [PemesananController::class, 'lihat'])->name('pemesanan.lihat');

    // Profil
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/** ADMIN */
Route::prefix('admin')->middleware(['auth', 'can:admin'])->name('admin.')->group(function () {
    Route::resource('jenis-tes',     JenisTesAdminController::class);
    Route::resource('slot-waktu',    SlotWaktuAdminController::class);
    Route::resource('fakultas',      FakultasAdminController::class);
    Route::resource('program-studi', ProgramStudiAdminController::class);

    Route::get('pemesanan',                     [PemesananAdminController::class, 'indeks'])->name('pemesanans.index');
    Route::get('pemesanan/{pemesanan}',         [PemesananAdminController::class, 'tampil'])->name('pemesanans.show');
    Route::post('pemesanan/{pemesanan}/status', [PemesananAdminController::class, 'perbaruiStatus'])->name('pemesanans.perbarui_status');
});

require __DIR__ . '/auth.php';
