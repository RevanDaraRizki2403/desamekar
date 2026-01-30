<?php

use App\Http\Controllers\AduanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengajuanDokumenController;
use App\Models\Resident;
use App\Models\Aduan;
use App\Models\PengajuanDokumen;
use Illuminate\Support\Facades\Route;

/* --- AUTH --- */
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
Route::get('/register', [AuthController::class, 'registerView'])->name('registerView');
Route::post('/register', [AuthController::class, 'register'])->name('register');

/* --- FITUR UTAMA (Wajib Login) --- */
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $residentId = Auth::user()->resident->id ?? null;
        $total_penduduk = Resident::count();
        $aduan_selesai  = Aduan::where('status', 'completed')->count();
        $recent_aduan   = Aduan::with('resident')->latest()->paginate(5, ['*'], 'page_aduan');

        $total_pengajuan_saya = PengajuanDokumen::where('resident_id', $residentId)->count();
        $total_aduan_saya     = Aduan::where('resident_id', $residentId)->count();
        $layanan_selesai      = PengajuanDokumen::where('resident_id', $residentId)->where('status', 'selesai')->count();
        $recent_pengajuan     = PengajuanDokumen::where('resident_id', $residentId)->latest()->paginate(5, ['*'], 'page_ajuan');

        return view('pages.dashboard', compact(
            'total_penduduk', 'aduan_selesai', 'recent_aduan',
            'total_pengajuan_saya', 'total_aduan_saya', 'layanan_selesai', 'recent_pengajuan'
        ));
    })->name('dashboard');

    /* --- KHUSUS ADMIN --- */
    Route::middleware('role:Admin')->group(function () {
        Route::get('/resident', [ResidentController::class, 'index'])->name('resident.index');
        Route::get('/resident/create', [ResidentController::class, 'create'])->name('resident.create');
        Route::post('/resident', [ResidentController::class, 'store'])->name('resident.store');
        Route::get('/resident/{id}/edit', [ResidentController::class, 'edit'])->name('resident.edit');
        Route::put('/resident/{id}', [ResidentController::class, 'update'])->name('resident.update');
        Route::delete('/resident/{id}', [ResidentController::class, 'destroy'])->name('resident.destroy');

        Route::get('/account-request', [UserController::class, 'account_request_view'])->name('account.request');
        Route::post('/account-request/approval/{id}', [UserController::class, 'account_approval'])->name('account.approval');
        Route::get('/account-list', [UserController::class, 'account_list_view'])->name('account.list');
        Route::post('/account-list/aktifkan/{id}', [UserController::class, 'account_aktifkan'])->name('account.aktifkan');
        Route::post('/account-list/nonaktif/{id}', [UserController::class, 'account_nonaktif'])->name('account.nonaktif');

        // Pengajuan Dokumen Masuk (Admin)
        Route::get('/pengajuan-masuk', [PengajuanDokumenController::class, 'adminIndex'])->name('pengajuan.masuk');
        Route::post('/admin/pengajuan/update/{id}', [PengajuanDokumenController::class, 'updateStatus'])->name('pengajuan.update_status');
    });

    /* --- KHUSUS USER (WARGA) --- */
    Route::middleware('role:User')->group(function () {
        Route::get('/pengajuan-dokumen', [PengajuanDokumenController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan-dokumen/create', [PengajuanDokumenController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan-dokumen/store', [PengajuanDokumenController::class, 'store'])->name('pengajuan.store');
    });

    /* --- ADMIN & USER --- */
    Route::middleware('role:Admin,User')->group(function () {
        Route::get('/Aduan', [AduanController::class, 'index'])->name('Aduan.index');
        Route::get('/Aduan/create', [AduanController::class, 'create'])->name('Aduan.create');
        Route::post('/Aduan', [AduanController::class, 'store'])->name('Aduan.store');
        Route::delete('/pengajuan-dokumen/{id}', [PengajuanDokumenController::class, 'destroy'])->name('pengajuan.destroy');
    });

}); // Penutup Middleware Auth