<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; // <-- tambahkan ini
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\Admin\DasborAdminController;
use App\Http\Controllers\Admin\JenisTesAdminController;
use App\Http\Controllers\Admin\SlotWaktuAdminController;
use App\Http\Controllers\Admin\FakultasAdminController;
use App\Http\Controllers\Admin\ProgramStudiAdminController;
use App\Http\Controllers\Admin\PemesananAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Payment\MidtransWebhookController;
use App\Http\Controllers\Admin\CheckinController;
use App\Http\Controllers\Admin\HasilTesController;
use App\Http\Controllers\Admin\PemesananQrController;
use App\Models\HasilTes;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\UserAdminController;



/** PUBLIC */
Route::view('/', 'beranda')->name('beranda');


Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('auth.google.callback');

/** DASHBOARD */
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

    // 🔹 PENTING: route sukses HARUS sebelum {kode}
    Route::get('/pemesanan/sukses',   [PemesananController::class, 'sukses'])->name('pemesanan.sukses');

    Route::get('/pemesanan/berkas/{berkas}', [PemesananController::class, 'lihatBerkas'])
        ->name('pemesanan.berkas');

    Route::get('/pemesanan/{kode}',   [PemesananController::class, 'lihat'])->name('pemesanan.lihat');
    Route::post('/pemesanan/checkout', [PemesananController::class, 'checkout'])->name('pemesanan.checkout');

    Route::get('/pemesanan/{pemesanan}/qr', [PemesananQrController::class, 'show'])
        ->name('pemesanan.qr');

    Route::get('/pemesanan/{kode}/bayar', [PemesananController::class, 'bayarLanjut'])
        ->name('pemesanan.bayar.lanjut');

    Route::get('/pemesanan/hasil/{hasilTes}', [PemesananController::class, 'lihatHasil'])
        ->name('pemesanan.hasil');

    // callback sukses (dev tanpa webhook)
    Route::get('/pemesanan/sukses', [PemesananController::class, 'sukses'])
        ->name('pemesanan.sukses');
    // Profil
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::post('/webhook/midtrans', [MidtransWebhookController::class, 'handle'])
    ->name('webhook.midtrans');

/** ADMIN */
Route::prefix('admin')
    ->middleware(['auth', 'can:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DasborAdminController::class, 'index'])->name('dasbor_admin');

        // Resource...
        Route::resource('jenis-tes', JenisTesAdminController::class)
            ->parameters(['jenis-tes' => 'jenisTes']);
        Route::resource('slot-waktu', SlotWaktuAdminController::class)
            ->parameters(['slot-waktu' => 'slotWaktu']);
        Route::resource('fakultas', FakultasAdminController::class)
            ->parameters(['fakultas' => 'fakultas']);
        Route::resource('program-studi', ProgramStudiAdminController::class)
            ->parameters(['program-studi' => 'programStudi']);

        // Pemesanan admin
        Route::get('pemesanan', [PemesananAdminController::class, 'indeks'])
            ->name('pemesanans.index');

        Route::get('pemesanan/{pemesanan}', [PemesananAdminController::class, 'tampil'])
            ->name('pemesanans.show')
            ->whereUuid('pemesanan');

        Route::get('/pemesanans/berkas/{berkas}', [PemesananAdminController::class, 'lihatBerkas'])
            ->name('pemesanans.berkas.show');


        Route::post('pemesanan/{pemesanan}/status', [PemesananAdminController::class, 'perbaruiStatus'])
            ->name('pemesanans.perbarui_status')
            ->whereUuid('pemesanan');

        // QR Check-in (INI YANG PENTING)
        Route::get('pemesanan/{pemesanan}/qr', [PemesananQrController::class, 'show'])
            ->name('pemesanans.qr')
            ->whereUuid('pemesanan');

        Route::get('pemesanan/{pemesanan}/qr/download', [PemesananQrController::class, 'download'])
            ->name('pemesanans.qr.download')
            ->whereUuid('pemesanan');

        Route::post('pemesanan/{pemesanan}/hasil', [HasilTesController::class, 'store'])
            ->name('pemesanans.hasil.store')
            ->whereUuid('pemesanan');

        Route::resource('users', UserAdminController::class)
            ->except(['show']) // ⬅️ tambahkan ini
            ->names('users')
            ->parameters(['users' => 'user']);

        Route::get('users/{user}', [UserAdminController::class, 'show'])
            ->name('users.show');

        // Checkin scan
        Route::post('checkin', [CheckinController::class, 'scan'])
            ->name('checkin.scan');
    });

require __DIR__ . '/auth.php';
