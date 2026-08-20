<?php

use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/tags/select2', [TagController::class, 'select2']);
Route::post('/tags', [TagController::class, 'store']);