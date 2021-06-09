<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| USER API Routes
|--------------------------------------------------------------------------
*/

/*
|------------------------------------------------------------------------
| ADMIN API Route 
|------------------------------------------------------------------------
*/
 
Route::prefix('admin')->group(function () {
    Route::post('/login', [ AuthController::class, 'login' ])->name('admin.login');
    Route::post('/register', [ AuthController::class, 'register' ])->name('admin.register');

    Route::middleware(['auth:admin-api'])->group(function () {
        Route::apiResource('products', ProductController::class);
    });
});