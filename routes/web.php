<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/list-products', [App\Http\Controllers\ProductController::class, 'index'])->name('list');
Route::get('/view-product/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('view');

Route::middleware(['Admin'])->group(function () {
	
	Route::get('/add-products', [App\Http\Controllers\ProductController::class, 'create'])->name('add');
	
	Route::get('/edit-product/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('editProduct')->where('id', '([0-9]+)');
	Route::post('/save-product', [App\Http\Controllers\ProductController::class, 'store'])->name('saveProduct');
	Route::post('/delete-product', [App\Http\Controllers\ProductController::class, 'destroy'])->name('deleteProduct');
	Route::post('/delete-product-image', [App\Http\Controllers\ProductController::class, 'deleteImage'])->name('deleteProductImage');
	
});