<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Public\HomeController as PublicHomeController;
use Illuminate\Support\Facades\Route;




// Public Routes
Route::get('/', [PublicHomeController::class, 'index'])->name('home');



// Super Admin, Admin, User Dashboard Routes
Route::middleware(['auth', 'verified', 'role:super_admin|admin|user'])->prefix('admin-dashboard')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

});


// Super Admin Dashboard Routes
Route::middleware(['auth', 'verified', 'role:super_admin'])->prefix('admin-dashboard')->group(function () {

    Route::resource('roles', RoleController::class)->names('admin.roles');

    Route::resource('permissions', PermissionController::class)->names('admin.permissions');

    Route::resource('users', UserController::class)->names('admin.users');
    Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('admin.users.status');

});


/*
Route::middleware(['permission:manage users'])->group(function () {
    // Additional routes that require 'manage users' permission can be added here
});
*/



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
