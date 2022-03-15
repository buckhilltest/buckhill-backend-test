<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
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
Route::group(['middleware' => 'api', 'prefix' => 'v1'], function () {
    Route::prefix('admin')->group(function () {
        Route::middleware('api')->group(function () {
            Route::post('/login', [AuthController::class, 'login']);

            Route::middleware('auth')->group(function () {
                Route::post('/create', [AuthController::class, 'register']);
                Route::post('/logout', [AuthController::class, 'logout']);
                Route::post('/refresh', [AuthController::class, 'refresh']);
                Route::get('/user-profile', [AdminController::class, 'userProfile']);
                Route::get('/user-listing', [AdminController::class, 'userListing']);
                Route::put('/user-edit/{uuid}', [AdminController::class, 'userEdit']);
                Route::delete('/user-delete/{uuid}', [AdminController::class, 'userDelete']);
            });
        });
    });
});
