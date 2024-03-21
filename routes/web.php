<?php

use App\Http\Controllers\AdminApiController;
use App\Http\Controllers\CustomerApiController;
use App\Http\Controllers\OrderApiController;
use App\Http\Controllers\ProductApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[ProductApiController::class,'index'])->name('front.index');
Route::get('/get-products',[ProductApiController::class,'getProducts'])->name('front.getProducts');
Route::get('/get-orders',[OrderApiController::class,'getOrders'])->name('front.getOrders');
Route::get('/get-customers',[CustomerApiController::class,'getCustomers'])->name('front.getCustomers');


