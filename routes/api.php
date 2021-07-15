<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\CartController as UserCartController;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

/*
|--------------------------------------------------------------------------
| PUBLIC API Routes
|--------------------------------------------------------------------------
*/

Route::get('/products', [ ProductController::class, 'index']);
Route::get('/products/{product}', [ ProductController::class, 'show']);
Route::get('/categories', [ CategoryController::class, 'index']);
Route::post('/register', [ UserAuthController::class, 'register' ])->name('user.register');
Route::post('/login', [ UserAuthController::class, 'login' ])->name('user.login');

/*
|--------------------------------------------------------------------------
| USER API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('carts', UserCartController::class)->except(['show']);
});

/*
|------------------------------------------------------------------------
| ADMIN API Route 
|------------------------------------------------------------------------
*/
 
Route::prefix('admin')->group(function () {
    Route::post('/register', [ AdminAuthController::class, 'register' ])->name('admin.register');
    Route::post('/login', [ AdminAuthController::class, 'login' ])->name('admin.login');

    Route::middleware(['auth:api-admins'])->group(function () {
        Route::apiResources([
            'products' => AdminProductController::class,
            'categories' => AdminCategoryController::class,
        ]);
    });
});