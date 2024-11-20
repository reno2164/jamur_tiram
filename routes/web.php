<?php

use App\Models\User;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\AuthPegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\pegawai\PegawaiController;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/shop', [UserController::class, 'shop'])->name('shop');
Route::get('/checkout', [UserController::class, 'checkout'])->name('checkout');
Route::get('/contact', [UserController::class, 'contact'])->name('contact');
Route::get('DetailProduk/{id}', [UserController::class, 'detailProduk'])->name('product.detail');




Route::middleware(['auth'])->group(function(){
    Route::get('/keranjang', [UserController::class, 'showCart'])->name('cart');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    Route::get('/order-success/{transactionId}', [CheckoutController::class, 'orderSuccess'])->name('orderSuccess');
    Route::post('/keranjang/update', [UserController::class, 'updateCart'])->name('updateCart');
    Route::get('/keranjang/remove/{cartId}', [UserController::class, 'removeFromCart'])->name('removeFromCart');
    Route::resource('addresses', AddressController::class);
    Route::POST('/addTocart', [UserController::class, 'addTocart'])->name('addTocart');
});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin',[AdminController::class,'index'])->name('admin.index');
    Route::resource('admin/product', ProductController::class);
});

Route::middleware(['auth',AuthPegawai::class])->group(function(){
    Route::get('/pegawai',[PegawaiController::class,'index'])->name('pegawai.index');
});