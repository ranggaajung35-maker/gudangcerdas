<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['destroy', 'show']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:admin')->name('categories.destroy');

    Route::resource('suppliers', SupplierController::class)->except(['destroy', 'show']);
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->middleware('role:admin')->name('suppliers.destroy');

    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('role:admin')->name('products.destroy');

    Route::resource('stock-movements', StockMovementController::class)->except(['show']);
    Route::delete('/stock-movements/{stockMovement}', [StockMovementController::class, 'destroy'])
        ->middleware('role:admin')->name('stock-movements.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
