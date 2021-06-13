<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\AuthController as UserAuthController;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| USER API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->group(function () {
    Route::post('/register', [ UserAuthController::class, 'register' ])->name('user.register');
    Route::post('/login', [ UserAuthController::class, 'login' ])->name('user.login');
});

/*
|------------------------------------------------------------------------
| ADMIN API Route 
|------------------------------------------------------------------------
*/
 
Route::prefix('admin')->group(function () {
    Route::post('/register', [ AdminAuthController::class, 'register' ])->name('admin.register');
    Route::post('/login', [ AdminAuthController::class, 'login' ])->name('admin.login');

    Route::middleware(['auth:admin-api'])->group(function () {
        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
    });
});