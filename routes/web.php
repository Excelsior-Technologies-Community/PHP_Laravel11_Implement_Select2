<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerProductsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| AUTHENTICATED PRODUCT ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Trash
    |--------------------------------------------------------------------------
    */

    Route::get(
        'products/trashed',
        [ProductController::class, 'trashed']
    )->name('products.trashed');


    /*
    |--------------------------------------------------------------------------
    | CSV Export
    |--------------------------------------------------------------------------
    */

    Route::get(
        'products/export/csv',
        [ProductController::class, 'exportCsv']
    )->name('products.export');


    /*
    |--------------------------------------------------------------------------
    | Bulk Actions
    |--------------------------------------------------------------------------
    */

    Route::post(
        'products/bulk-delete',
        [ProductController::class, 'bulkDelete']
    )->name('products.bulkDelete');

    Route::post(
        'products/bulk-status',
        [ProductController::class, 'bulkStatus']
    )->name('products.bulkStatus');


    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    Route::put(
        'products/{product}/restore',
        [ProductController::class, 'restore']
    )->name('products.restore');


    /*
    |--------------------------------------------------------------------------
    | Force Delete
    |--------------------------------------------------------------------------
    */

    Route::delete(
        'products/{product}/force-delete',
        [ProductController::class, 'forceDelete']
    )->name('products.forceDelete');


    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    Route::post(
        'products/{product}/toggle-status',
        [ProductController::class, 'toggleStatus']
    )->name('products.toggleStatus');


    /*
    |--------------------------------------------------------------------------
    | Product CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'products',
        ProductController::class
    );
});


/*
|--------------------------------------------------------------------------
| CUSTOMER PRODUCT ROUTES
|--------------------------------------------------------------------------
*/

Route::get(
    '/customer/products',
    [CustomerProductsController::class, 'index']
)->name('customer.products');

Route::get(
    '/customer/products/{product}',
    [CustomerProductsController::class, 'show']
)->name('customer.products.show');


/*
|--------------------------------------------------------------------------
| TAG ROUTES
|--------------------------------------------------------------------------
*/

Route::resource(
    'tags',
    TagController::class
);


/*
|--------------------------------------------------------------------------
| SELECT2 AJAX API
|--------------------------------------------------------------------------
*/

Route::get(
    '/api/products/select2',
    [ProductController::class, 'select2Search']
);

Route::get(
    '/api/tags/select2',
    [TagController::class, 'select2Search']
);


/*
|--------------------------------------------------------------------------
| HOMEPAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    function () {
        return view('dashboard');
    }
)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});


require __DIR__ . '/auth.php';
