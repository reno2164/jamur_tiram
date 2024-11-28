<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        
        $carts = Auth::user()->carts->with('product')->get();

        return view('user.cart', compact('carts'));
    }
    public function update(Request $request)
    {
        $cart = Cart::findOrFail($request->cart_id);
        $cart->update([
            'quantity' => $request->quantity,
            'price' => $cart->product->price * $request->quantity,
        ]);

        return response()->json(['success' => true, 'total_price' => $cart->price]);
    }

    public function updateQuantity(Request $request)
    {
    $request->validate([
        'cart_id' => 'required|exists:carts,id',
        'quantity' => 'required|numeric|min:0.1',
    ]);

    $cart = Cart::findOrFail($request->cart_id);
    $product = $cart->product;

    // Validasi stok
    if ($request->quantity > $product->stok) {
        return response()->json([
            'success' => false,
            'message' => 'Kuantitas melebihi stok yang tersedia.',
        ]);
    }

    // Update quantity dan total harga
    $cart->quantity = $request->quantity;
    $cart->price = $product->price * $request->quantity;
    $cart->save();

    return response()->json([
        'success' => true,
        'message' => 'Kuantitas berhasil diperbarui.',
        'total_price_item' => number_format($cart->price, 0, ',', '.'),
    ]);
}

    public function processCheckout(Request $request)
    {
        $cartIds = $request->cart_ids;
        session(['checkout_items' => $cartIds]);

        return redirect()->route('checkout.index');
    }
}
