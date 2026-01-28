<?php

use App\Http\Controllers\AduanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\UserController;
use App\Models\Resident;
use App\Models\Aduan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
Route::get('/register', [AuthController::class, 'registerView'])->name('registerView');
Route::post('/register', [AuthController::class, 'register'])->name('register');

/*
|--------------------------------------------------------------------------
| DASHBOARD (DIPERBAIKI DENGAN LOGIKA DATA)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    // Menghitung statistik untuk ditampilkan di kartu dashboard
    $total_penduduk = Resident::count();
    $total_aduan    = Aduan::count();
    $aduan_proses   = Aduan::where('status', 'processing')->count();
    $aduan_selesai  = Aduan::where('status', 'completed')->count();

    // Ambil 5 aktivitas pengaduan terbaru
    $recent_aduan   = Aduan::with('resident')->latest()->take(5)->get();

    // Mengirimkan semua data ke view dashboard
    return view('pages.dashboard', compact(
        'total_penduduk', 
        'total_aduan', 
        'aduan_proses', 
        'aduan_selesai', 
        'recent_aduan'
    ));
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| RESIDENT (ADMIN ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('role:Admin')->group(function () {
    Route::get('/resident', [ResidentController::class, 'index'])->name('resident.index');
    Route::get('/resident/create', [ResidentController::class, 'create'])->name('resident.create');
    Route::post('/resident', [ResidentController::class, 'store'])->name('resident.store');
    Route::get('/resident/{id}/edit', [ResidentController::class, 'edit'])->name('resident.edit');
    Route::put('/resident/{id}', [ResidentController::class, 'update'])->name('resident.update');
    Route::delete('/resident/{id}', [ResidentController::class, 'destroy'])->name('resident.destroy');
});

/*
|--------------------------------------------------------------------------
| ACCOUNT REQUEST (ADMIN ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('role:Admin')->group(function () {
    Route::get('/account-request', [UserController::class, 'account_request_view'])
        ->name('account.request');

    Route::post('/account-request/approval/{id}', [UserController::class, 'account_approval'])
        ->name('account.approval');
      
    Route::get('/account-list', [UserController::class, 'account_list_view'])
        ->name('account.list');

    Route::post('/account-list/aktifkan/{id}', [UserController::class, 'account_aktifkan'])
        ->name('account.aktifkan');

    Route::post('/account-list/nonaktif/{id}', [UserController::class, 'account_nonaktif'])
        ->name('account.nonaktif');

    Route::get('/user/{id}', [UserController::class, 'show'])
        ->name('user.show');
});

/*
|--------------------------------------------------------------------------
| ADUAN (ADMIN & USER)
|--------------------------------------------------------------------------
*/
Route::middleware('role:Admin,User')->group(function () {
    Route::get('/Aduan', [AduanController::class, 'index'])->name('Aduan.index');
    Route::get('/Aduan/create', [AduanController::class, 'create'])->name('Aduan.create');
    Route::post('/Aduan', [AduanController::class, 'store'])->name('Aduan.store');
    Route::get('/Aduan/{id}/edit', [AduanController::class, 'edit'])->name('Aduan.edit');
    Route::put('/Aduan/{id}', [AduanController::class, 'update'])->name('Aduan.update');
    Route::delete('/Aduan/{id}', [AduanController::class, 'destroy'])->name('Aduan.destroy');
});

/*
|--------------------------------------------------------------------------
| UPDATE STATUS ADUAN (ADMIN ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('role:Admin')->group(function () {
    // Rute update status dan tanggapan admin desa
    Route::post('/aduan/update-status/{id}', [AduanController::class, 'update_status'])->name('Aduan.update_status');
});