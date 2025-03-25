<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::post('auth/login', 'login');
    Route::post('auth/register', 'register');
});

Route::get('/auth/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/products', App\Http\Controllers\API\ProductController::class);
Route::apiResource('/users', App\Http\Controllers\API\UserController::class);

Route::apiResource('/categories', App\Http\Controllers\API\CategoryController::class);
Route::apiResource('/transactions', App\Http\Controllers\API\TransactionController::class);
