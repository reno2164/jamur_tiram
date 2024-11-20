<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index() 
    {
        $data = Product::latest()->paginate(10);
        $best = product::where('qty_out', '>=', 5)->get();
        $countKeranjang = Auth::check() ? Auth::user()->carts->count() : 0;
        
        return view('user.index', [
            'title' => 'home',
            'data' => $data,
            'best' => $best,
            'count'=> $countKeranjang
        ]);
    }
    public function detailProduk(string $id)
    {
        $product = Product::findOrFail($id);
        $title = 'Detail Produk';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        $isInCart = Cart::where('user_id', Auth::id())
                                                        ->where('product_id', $product->id)
                                                        ->exists();

        // Pass product data to the view
        return view('user.detailproduk', compact('product','title','isInCart','count'));
    }
    public function addTocart(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // Pastikan product_id valid
            'quantity' => 'required|integer|min:1', // Pastikan quantity adalah integer >= 1
        ]);

        $userId = Auth::id(); // Ambil ID pengguna yang sedang login

        // Ambil data produk berdasarkan ID
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Tambahkan atau perbarui data di tabel cart
        $cart = Cart::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
            ],
            [
                'quantity' => $validated['quantity'],
                'price' => $product->price, // Harga diambil dari tabel products
            ]
        );

        // Berikan pesan keberhasilan atau kegagalan
        if ($cart) {
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan produk ke keranjang.');
    }


    public function shop()
    {
        $countKeranjang = Auth::check() ? Auth::user()->carts->count() : 0;

        $data = Product::paginate(10);
        return view('user.shop', [
            'data' => $data,
            'count'     => $countKeranjang,
            'title' => 'shop'
        ]);
    }

    public function checkOut() 
    {
        if (!Auth::check()) {
            redirect()->route('login');
        }    
    }
    public function showCart()
{
    $count = Auth::check() ? Auth::user()->carts->count() : 0;
    // Ambil produk dalam keranjang berdasarkan pengguna yang sedang login
    $carts = Cart::where('user_id', Auth::id())->get();
    $title = 'Keranjang';
    return view('user.cart', compact('carts','title','count'));
}

public function updateCart(Request $request)
{
    // Validasi data yang dikirim
    $validated = $request->validate([
        'quantity' => 'array|required',
        'quantity.*' => 'numeric|min:0.1', // Pastikan kuantitas berupa angka dengan desimal
        'cart_items' => 'array', // Pilihan produk yang dicentang
    ]);

    // Update kuantitas produk dalam keranjang
    foreach ($validated['quantity'] as $cartId => $quantity) {
        $cart = Cart::find($cartId);
        if ($cart) {
            $cart->quantity = $quantity;
            $cart->save();
        }
    }

    // Redirect ke halaman keranjang dengan pesan sukses
    return redirect()->route('cart')->with('success', 'Keranjang berhasil diperbarui.');
}

public function removeFromCart($cartId)
{
    // Hapus produk dari keranjang
    $cart = Cart::find($cartId);
    if ($cart) {
        $cart->delete();
    }

    return redirect()->route('cart')->with('success', 'Produk berhasil dihapus dari keranjang.');
}

}
