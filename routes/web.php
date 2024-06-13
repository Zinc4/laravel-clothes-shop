<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name("home");
Route::get('/product/{id}', [ProductController::class, 'show'])->name("product");

Route::post('/checkout', [CheckoutController::class, 'process'])->name("checkout-process");
Route::get('/checkout/{transaction}', [CheckoutController::class, 'checkout'])->name("checkout");
Route::get('/checkout/success/{transaction}', [CheckoutController::class, 'success'])->name("checkout-success");

Route::get('/transactions', [TransactionController::class, 'index'])->name("transactions");

Auth::routes();

Route::get('/admin/products', [AdminController::class, 'index'])->name("admin.products.index");
Route::get('/admin/products/create', [AdminController::class, 'create'])->name("admin.products.create");
Route::get('/admin/products/{id}', [AdminController::class, 'show'])->name("admin.products.show");
Route::get('/admin/products/{id}/edit', [AdminController::class, 'edit'])->name("admin.products.edit");
Route::post('/admin/products', [AdminController::class, 'store'])->name("admin.products.store");
Route::put('/admin/products/{id}', [AdminController::class, 'update'])->name("admin.products.update");
Route::delete('/admin/products/{id}', [AdminController::class, 'destroy'])->name("admin.products.destroy");
