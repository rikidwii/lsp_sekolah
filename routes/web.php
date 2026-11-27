<?php

use App\Http\Controllers\Admin\VerifikasiPendaftaranController;
use App\Http\Controllers\Asesi\SkemaController;
use App\Http\Controllers\Asesor\AsesiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaceholderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesi\AsesiDashboardController;
use App\Http\Controllers\Asesi\JadwalController;
use App\Http\Controllers\Asesi\HasilController;
use App\Http\Controllers\Asesi\SertifikatController;
use App\Http\Controllers\Asesor\AsesorDashboardController;
use App\Http\Controllers\Asesor\JadwalAsesmenController;
use App\Http\Controllers\Asesi\ProfilController;

Route::name('guest.')->group(function () {
    Route::get('/', fn () => view('guest.index'))->name('index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register/asesi', [RegisterController::class, 'storeAsesi'])->name('register.asesi');
    Route::post('/register/asesor', [RegisterController::class, 'storeAsesor'])->name('register.asesor');
});

Route::get('/register/berhasil', [RegisterController::class, 'success'])->name('register.success');
Route::get('/register/menunggu-verifikasi', [RegisterController::class, 'pending'])->name('register.pending');

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirectHome'])->name('dashboard');
    Route::get('/coming-soon', [PlaceholderController::class, 'show'])->name('placeholder');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::get('/verifikasi-pendaftaran', [VerifikasiPendaftaranController::class, 'index'])->name('verifikasi.index');
        Route::patch('/verifikasi-pendaftaran/{asesorProfile}/approve', [VerifikasiPendaftaranController::class, 'approve'])->name('verifikasi.approve');
        Route::patch('/verifikasi-pendaftaran/{asesorProfile}/reject', [VerifikasiPendaftaranController::class, 'reject'])->name('verifikasi.reject');
    });

    Route::middleware('role:asesor')->prefix('asesor')->name('asesor.')->group(function () {
        Route::get('/dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/daftar-asesi', [AsesiController::class, 'index'])->name('asesi.index');
        Route::get('/jadwal', [JadwalAsesmenController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/export', [JadwalAsesmenController::class, 'export'])->name('jadwal.export');
    });

    Route::middleware('role:asesi')->prefix('asesi')->name('asesi.')->group(function () {
    Route::get('/dashboard', [AsesiDashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifikasi/baca', [AsesiDashboardController::class, 'bacaSemua'])->name('notifikasi.baca');
    Route::get('/skema', [SkemaController::class, 'index'])->name('skema.index');
    Route::view('/skema/daftar',  'asesi.skema.daftar')->name('skema.daftar');
    Route::view('/skema/detail',  'asesi.skema.detail')->name('skema.detail');
    // nanti saat backend jadi:
    // Route::post('/skema/daftar', [SkemaController::class, 'store'])->name('skema.daftar.store');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::get('/hasil/{id}', [HasilController::class, 'show'])->name('hasil.show');
    Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/{sertifikat}/lihat', [SertifikatController::class, 'lihat'])->name('sertifikat.lihat');
    Route::get('/sertifikat/{sertifikat}/unduh', [SertifikatController::class, 'unduh'])->name('sertifikat.unduh');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
Route::delete('/profil/foto', [ProfilController::class, 'destroyFoto'])->name('profil.foto.destroy');
Route::post('/profil/sinkron-dapodik', [ProfilController::class, 'syncDapodik'])->name('profil.sync');
    });
});