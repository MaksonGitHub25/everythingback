<?php

use Illuminate\Support\Facades\Route;

Route::get('/customers', 'App\Http\Controllers\API\CustomersController@getData');
Route::post('/customers', 'App\Http\Controllers\API\CustomersController@sendData');

Route::get('/products', 'App\Http\Controllers\API\ProductsController@getData');
Route::post('/products', 'App\Http\Controllers\API\ProductsController@sendData');
