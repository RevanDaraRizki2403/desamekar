<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\UserController;
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
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware('role:Admin,User');

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
      
Route::middleware('role:Admin')->group(function () {

    Route::get('/account-list', [UserController::class, 'account_list_view'])
        ->name('account.list');

    Route::post('/account-list/aktifkan/{id}', [UserController::class, 'account_aktifkan'])
        ->name('account.aktifkan');

    Route::post('/account-list/nonaktif/{id}', [UserController::class, 'account_nonaktif'])
        ->name('account.nonaktif');

});

});
