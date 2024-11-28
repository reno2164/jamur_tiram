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
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\pegawai\PegawaiController;
use App\Http\Controllers\ProductController;


Auth::routes();
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/shop', [UserController::class, 'shop'])->name('shop');
Route::get('/kontak', [UserController::class, 'contact'])->name('contact');
Route::get('DetailProduk/{id}', [UserController::class, 'detailProduk'])->name('product.detail');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.proses'); 
Route::get('/checkout/success/{transaction_code}', [CheckoutController::class, 'success'])->name('checkout.success');





Route::middleware(['auth'])->group(function(){
    Route::get('/keranjang', [UserController::class, 'showCart'])->name('cart');
    Route::post('/keranjang/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.proses'); 
    Route::get('/checkout/success/{transaction_code}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/keranjang/update', [UserController::class, 'updateCart'])->name('updateCart');
    Route::get('/keranjang/remove/{cartId}', [UserController::class, 'removeFromCart'])->name('removeFromCart');
    Route::resource('addresses', AddressController::class);
    Route::POST('/addTocart', [UserController::class, 'addTocart'])->name('addTocart');
    Route::get('/checkout/pay/{transaction_code}', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/riwayat-pembelian', [UserController::class, 'riwayat'])->name('riwayat');
    Route::get('/pesanan', [UserController::class, 'pesanan'])->name('pesanan.index');
    Route::delete('transactions/cancel/{transaction_id}', [UserController::class, 'cancel'])->name('transactions.cancel');
    Route::get('/pesanan/detail/{transaction_code}', [UserController::class, 'show'])->name('orders.detail');
});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    
});

Route::middleware(['auth',AuthPegawai::class])->group(function(){
    Route::get('/pegawai',[PegawaiController::class,'index'])->name('pegawai.index');
});
Route::middleware([RoleMiddleware::class.':ADM,PGW'])->group(function(){
    Route::get('/admin',[AdminController::class,'index'])->name('admin.index');
    Route::resource('admin/product', ProductController::class);
    Route::get('/admin/pesanan',[AdminController::class,'pesanan'])->name('admin.pesanan');
    Route::get('/admin/detail-pesanan/{id}', [AdminController::class, 'show'])->name('admin.orders.show');
    Route::post('/product/{product}/add-stock', [ProductController::class, 'addStock'])->name('product.addStock');
    Route::patch('/orders/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('admin/DataPenjualan',[AdminController::class,'dataPenjualan'])->name('admin.datapenjualan');
    Route::get('admin/detail-DataPenjualan/{id}', [AdminController::class, 'showDataPenjualan'])->name('admin.orders.showDetail');
    Route::get('/admin/orders/completed/pdf', [AdminController::class, 'downloadPdf'])->name('admin.orders.downloadPdf');
});