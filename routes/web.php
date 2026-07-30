<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerProductsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Product routes
Route::middleware(['auth'])->group(function () {
    Route::get('products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
    Route::post('products/bulk-status', [ProductController::class, 'bulkStatus'])->name('products.bulkStatus');
    Route::put('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{product}/force-delete', [ProductController::class, 'forceDelete'])->name('products.forceDelete');
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::resource('products', ProductController::class);
});

// Customer product routes
Route::get('/customer/products', [CustomerProductsController::class, 'index'])->name('customer.products');
Route::get('/customer/products/{product}', [CustomerProductsController::class, 'show'])->name('customer.products.show');

// Tag routes
Route::resource('tags', TagController::class);

// Select2 AJAX API routes
Route::get('/api/products/select2', [ProductController::class, 'select2Search']);
Route::get('/api/tags/select2', [TagController::class, 'select2Search']);

// Homepage
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';