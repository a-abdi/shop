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
use App\Http\Controllers\Admin\UserController as AdminUserController;

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
Route::post('/forgot-password', [ UserAuthController::class, 'forgotPassword' ])->name('user.forgotPassword');
Route::post('/reset-password', [ UserAuthController::class, 'resetPassword' ])->name('user.resetPassword');

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
    Route::post('/forgot-password', [ AdminAuthController::class, 'forgotPassword' ])->name('admin.forgotPassword');
    Route::post('/reset-password', [ AdminAuthController::class, 'resetPassword' ])->name('admin.resetPassword');

    Route::middleware(['auth:api-admins'])->group(function () {
        Route::post('/products/{productId}', [AdminProductController::class, 'update']);
        Route::apiResource('products', AdminProductController::class)->except(['update']);
        Route::apiResources([
            'categories'=> AdminCategoryController::class,
            'users' => AdminUserController::class,
        ]);
    });
});