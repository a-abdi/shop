<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

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
});