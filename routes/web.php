<?php

use App\Http\Controllers\LicenseAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/licenses');
});

Route::middleware('admin.auth')->prefix('admin')->group(function () {
    Route::get('/licenses', [LicenseAdminController::class, 'index'])->name('admin.licenses.index');
    Route::post('/licenses', [LicenseAdminController::class, 'store'])->name('admin.licenses.store');
    Route::post('/licenses/{id}/toggle-status', [LicenseAdminController::class, 'toggleStatus'])->name('admin.licenses.toggle-status');
    Route::post('/licenses/{id}/extend-validity', [LicenseAdminController::class, 'extendValidity'])->name('admin.licenses.extend-validity');
});
