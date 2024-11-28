<?php

use App\Models\User;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\AuthPegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\admin\AdminController;  // Hanya ini satu kali
use App\Http\Controllers\pegawai\PegawaiController;
use App\Http\Controllers\hashController;
use App\Http\Controllers\SAWController;
use App\Http\Controllers\ProductController;

Auth::routes();

Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/shop', [UserController::class, 'shop'])->name('shop');
Route::get('/contact', [UserController::class, 'contact'])->name('contact');
Route::get('DetailProduk/{id}', [UserController::class, 'detailProduk'])->name('product.detail');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.proses');
Route::get('/checkout/success/{transaction_code}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/api/midtrans/notification', [CheckoutController::class, 'handleNotification']);

Route::middleware(['auth'])->group(function () {
    Route::get('/keranjang', [UserController::class, 'showCart'])->name('cart');
    Route::post('/keranjang/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/keranjang/update', [UserController::class, 'updateCart'])->name('updateCart');
    Route::get('/keranjang/remove/{cartId}', [UserController::class, 'removeFromCart'])->name('removeFromCart');
    Route::resource('addresses', AddressController::class);
    Route::POST('/addTocart', [UserController::class, 'addTocart'])->name('addTocart');
    Route::get('/checkout/pay/{transaction_code}', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/riwayat-pembelian', [UserController::class, 'riwayat'])->name('riwayat');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('admin/product', ProductController::class);
    Route::post('/product/{product}/add-stock', [ProductController::class, 'addStock'])->name('product.addStock');
    Route::get('/admin/pesanan', [AdminController::class, 'pesanan'])->name('admin.pesanan');
    Route::put('/admin/pesanan/update/{id}', [AdminController::class, 'update'])->name('admin.transactions.update');
});

Route::middleware(['auth', AuthPegawai::class])->group(function () {
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
});

Route::get('/hash', function () {
    $password = "123";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    return "Password asli: " . $password . "<br>Password hash: " . $hashedPassword;
});

Route::get('/saw', [SAWController::class, 'index']);

Route::middleware(['auth', RoleMiddleware::class . ':ADM,PGW'])->group(function () {
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
});

Route::get('/admin/saw', [AdminController::class, 'saw'])->withoutMiddleware([AuthAdmin::class])->name('admin.saw');

