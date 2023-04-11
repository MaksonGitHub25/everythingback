<?php

use Illuminate\Support\Facades\Route;

Route::prefix('customers')->group(function () {
    Route::get('/', [App\Http\Controllers\API\CustomersController::class, 'getData']);
    Route::post('/saveData', [App\Http\Controllers\API\CustomersController::class, 'saveUserData']);
    Route::post('/register', [\App\Http\Controllers\Api\CustomersController::class, 'registerUser']);
    Route::post('/jwtLogin', [\App\Http\Controllers\API\CustomersController::class, 'loginUserByJWT']);
    Route::post('/dataLogin', [\App\Http\Controllers\API\CustomersController::class, 'loginUserByUserData']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [App\Http\Controllers\API\ProductsController::class, 'getData']);
    Route::post('/addNewProduct', [App\Http\Controllers\API\ProductsController::class, 'addNewProduct']);
});
