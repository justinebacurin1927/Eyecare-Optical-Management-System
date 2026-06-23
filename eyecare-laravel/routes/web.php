<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\LensTypeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleTransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    Route::middleware('role:Admin,Staff')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('products', ProductController::class)->except(['create', 'show', 'edit']);
        Route::post('frames', [FrameController::class, 'store'])->name('frames.store');
        Route::delete('frames/{frame}', [FrameController::class, 'destroy'])->name('frames.destroy');
        Route::post('lens-types', [LensTypeController::class, 'store'])->name('lens-types.store');
        Route::delete('lens-types/{lensType}', [LensTypeController::class, 'destroy'])->name('lens-types.destroy');
        Route::get('patients/search', [PatientController::class, 'search'])->name('patients.search');
        Route::resource('patients', PatientController::class);
        Route::resource('sales', SaleTransactionController::class)->except(['create', 'show', 'edit']);
        Route::get('sales/{sale}/edit', [SaleTransactionController::class, 'edit'])->name('sales.edit');
        Route::get('products/{product}/check', [SaleTransactionController::class, 'checkProduct'])->name('products.check');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
