<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\TernakController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// --------------------------------------------------------------------------
// Guest Routes (Autentikasi)
// --------------------------------------------------------------------------
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');

// --------------------------------------------------------------------------
// Authenticated Routes
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // DASHBOARD MASING-MASING AKTOR
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/karyawan/dashboard', [DashboardController::class, 'index'])->name('karyawan.dashboard');
    Route::get('/manager/dashboard', [DashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // DATA MASTER: DATA TERNAK
    Route::get('/data-ternak/download-template', [TernakController::class, 'downloadTemplate'])->name('data-ternak.download-template'); // Route Unduh Template Excel
    Route::post('/data-ternak/import', [TernakController::class, 'importExcel'])->name('data-ternak.import');
    Route::get('/data-ternak', [TernakController::class, 'index'])->name('data-ternak.index');
    Route::get('/data-ternak/create', [TernakController::class, 'create'])->name('data-ternak.create');
    Route::post('/data-ternak', [TernakController::class, 'store'])->name('data-ternak.store');
    Route::delete('/data-ternak/{id}', [TernakController::class, 'destroy'])->name('data-ternak.destroy');

    // Safety Redirect jika ada yang memanggil route edit lama
    Route::get('/data-ternak/{id}/edit', function() {
        return redirect()->route('data-ternak.index');
    })->name('data-ternak.edit');

    // Alias Route Ternak
    Route::get('/ternak/download-template', [TernakController::class, 'downloadTemplate'])->name('ternak.download-template'); // Alias Route Unduh Template
    Route::post('/ternak/import', [TernakController::class, 'importExcel'])->name('ternak.import');
    Route::get('/ternak', [TernakController::class, 'index'])->name('ternak.index');
    Route::get('/ternak/create', [TernakController::class, 'create'])->name('ternak.create');
    Route::post('/ternak/store', [TernakController::class, 'store'])->name('ternak.store');
    Route::delete('/ternak/{id}', [TernakController::class, 'destroy'])->name('ternak.destroy');

    Route::get('/ternak/{id}/edit', function() {
        return redirect()->route('data-ternak.index');
    })->name('ternak.edit');

    // DATA MASTER: DATA PEMERIKSAAN
    Route::get('/data-pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
    Route::post('/data-pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');
    Route::get('/data-pemeriksaan/{id}/edit', [PemeriksaanController::class, 'edit'])->name('pemeriksaan.edit');
    Route::put('/data-pemeriksaan/{id}', [PemeriksaanController::class, 'update'])->name('pemeriksaan.update');
    Route::delete('/data-pemeriksaan/{id}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaan.destroy');

    // ANALISIS & PREDIKSI (RANDOM FOREST)
    Route::get('/prediksi-kelayakan', [PrediksiController::class, 'index'])->name('prediksi.index');
    Route::get('/prediksi-kelayakan/detail/{id?}', [PrediksiController::class, 'detail'])->name('prediksi.detail');
    Route::post('/prediksi-kelayakan', [PrediksiController::class, 'proses'])->name('prediksi.proses');
    Route::get('/riwayat-prediksi', [PrediksiController::class, 'riwayat'])->name('prediksi.riwayat');

    // EVALUASI DATASET & MODEL ML
    Route::get('/dataset-evaluasi', [LaporanController::class, 'evaluasi'])->name('prediksi.dataset-evaluasi');
    Route::get('/evaluasi', [LaporanController::class, 'evaluasi'])->name('evaluasi.index'); // Alias route evaluasi

    // MODUL LAPORAN
    Route::get('/laporan-kelayakan', [LaporanController::class, 'kelayakan'])->name('laporan.kelayakan');
    Route::get('/laporan-kelayakan/pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.pdf');
    Route::get('/laporan-harga', [LaporanController::class, 'harga'])->name('laporan.harga');
    
    // Laporan Data Ternak (dengan alias)
    Route::get('/laporan-data-ternak', [LaporanController::class, 'dataTernak'])->name('laporan.data-ternak');
    Route::get('/laporan-ternak', [LaporanController::class, 'dataTernak'])->name('laporan.ternak');

    // Laporan Transaksi
    Route::get('/laporan-transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');

    // MANAJEMEN AKUN
    Route::get('/account-list', [UserController::class, 'account_list_view'])->name('account.list');
    Route::post('/account-list', [UserController::class, 'store'])->name('account.store');
    Route::post('/account/update-status/{id}', [UserController::class, 'update_account_status'])->name('account.update_status');

});