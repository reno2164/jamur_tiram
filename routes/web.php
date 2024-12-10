<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\ManagementuserController;
use App\Http\Controllers\ProductController;

Auth::routes();
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/shop', [UserController::class, 'shop'])->name('shop');
Route::get('/kontak', [UserController::class, 'contact'])->name('contact');
Route::get('DetailProduk/{id}', [UserController::class, 'detailProduk'])->name('product.detail');




Route::middleware(['auth'])->group(function(){
    Route::get('/keranjang', [UserController::class, 'showCart'])->name('cart');
    Route::post('/keranjang/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/proses', [CheckoutController::class, 'checkout'])->name('checkout.proses'); 
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

Route::middleware([RoleMiddleware::class.':ADM,PGW'])->group(function(){
    Route::get('/admin',[AdminController::class,'index'])->name('admin.index');
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::resource('admin/product', ProductController::class);
    Route::get('/admin/pesanan',[AdminController::class,'pesanan'])->name('admin.pesanan');
    Route::get('/admin/detail-pesanan/{id}', [AdminController::class, 'show'])->name('admin.orders.show');
    Route::post('/product/{product}/add-stock', [ProductController::class, 'addStock'])->name('product.addStock');
    Route::patch('/orders/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('admin/DataPenjualan',[AdminController::class,'dataPenjualan'])->name('admin.datapenjualan');
    Route::get('admin/detail-DataPenjualan/{id}', [AdminController::class, 'showDataPenjualan'])->name('admin.orders.showDetail');
    Route::get('/admin/orders/completed/pdf', [AdminController::class, 'downloadPdf'])->name('admin.orders.downloadPdf');
    Route::get('/admin/tpk', [AdminController::class, 'tpk'])->name('admin.tpk.index');
    Route::put('/admin/pesanan/update/{id}',[AdminController::class,'update'])->name('admin.transactions.update');
    Route::get('/admin/users', [ManagementuserController::class, 'manageUsers'])->name('manage.users');
    Route::get('/admin/users/create', [ManagementuserController::class, 'createUser'])->name('create.users');
    Route::post('/admin/users/store', [ManagementuserController::class, 'storeUser'])->name('store.user');
    Route::get('/admin/users/{id}/edit', [ManagementuserController::class, 'editUser'])->name('edit.users');
    Route::post('/admin/users/{id}/update', [ManagementuserController::class, 'updateUser'])->name('update.users');
    Route::delete('/admin/users/{id}', [ManagementuserController::class, 'deleteUser'])->name('delete.user');
});

