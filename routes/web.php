<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Public\HomeController as PublicHomeController;
use Illuminate\Support\Facades\Route;







// Public Routes
Route::get('/', [PublicHomeController::class, 'index'])->name('home');








// Admin Dashboard Routes
Route::middleware(['auth', 'verified'])->prefix('admin-dashboard')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->names('admin.users');

    Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('admin.users.status');
});






require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
