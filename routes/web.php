<?php

use Illuminate\Support\Facades\Route;

Route::prefix('customers')->group(function () {
    Route::get('/', [App\Http\Controllers\API\CustomersController::class, 'getData']);
    Route::post('/saveData', [App\Http\Controllers\API\CustomersController::class, 'saveUserData']);
    Route::post('/register', [\App\Http\Controllers\Api\CustomersController::class, 'registerUser']);
    Route::post('/jwtLogin', [\App\Http\Controllers\API\CustomersController::class, 'loginUserByJWT']);
    Route::post('/dataLogin', [\App\Http\Controllers\API\CustomersController::class, 'loginUserByUserData']);
});

Route::prefix('googleCustomers')->group(function () {
    Route::get('/', [App\Http\Controllers\API\GoogleCustomersController::class, 'getData']);
    Route::post('/register', [\App\Http\Controllers\Api\GoogleCustomersController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\GoogleCustomersController::class, 'login']);
    Route::post('/jwtLogin', [\App\Http\Controllers\API\GoogleCustomersController::class, 'loginByJWT']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [App\Http\Controllers\API\ProductsController::class, 'getData']);
    Route::get('/{productId}', [App\Http\Controllers\API\ProductsController::class, 'getProduct']);
    Route::post('/addNewProduct', [App\Http\Controllers\API\ProductsController::class, 'addNewProduct']);
    Route::get('/image/{filename}', [App\Http\Controllers\API\ProductsController::class, 'getImage']);
    Route::post('/deleteProduct/{productId}', [App\Http\Controllers\API\ProductsController::class, 'deleteProduct']);
});

Route::prefix('admins')->group(function () {
    Route::post('/checkAdminData', [App\Http\Controllers\API\AdminController::class, 'checkAdminData']);
});
