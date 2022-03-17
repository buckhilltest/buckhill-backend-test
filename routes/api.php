<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['auth', 'api'], 'prefix' => 'v1'], function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])
            ->name('login')
            ->withoutMiddleware(Authenticate::class);

        Route::post('/create', [AuthController::class, 'register'])
            ->name('register');
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user-profile', [AdminController::class, 'userProfile'])
            ->name('user-profile');
        Route::get('/user-listing', [AdminController::class, 'userListing'])
            ->name('user-listing');
        Route::put('/user-edit/{uuid}', [AdminController::class, 'userEdit'])
            ->name('user-editing');
        Route::delete('/user-delete/{uuid}', [AdminController::class, 'userDelete'])
            ->name('user-delete');
    });

    Route::apiResource('products', ProductController::class, [
        'parameters' => [
            'products' => 'uuid'
        ]
    ]);
});
