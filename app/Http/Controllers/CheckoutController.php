<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // Menampilkan halaman checkout
    public function index(Request $request)
    {
        $request->validate([
            'cart_items' => 'required|array|min:1',
            'cart_items.*' => 'exists:carts,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:1',
            'unit' => 'required|array',
            'unit.*' => 'in:kg,gram',
        ]);
        $title = 'checkout';
        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)->get();
        $addresses = Address::where('user_id', $user->id)->get();
        $count = Auth::check() ? Auth::user()->carts->count() : 0;

        return view('user.checkout', compact('carts', 'addresses', 'title', 'count'));
    }

    // Menangani proses checkout
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->get();
        $carts = Cart::where('user_id', $user->id)->get();
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        $title = 'checkout';
        // Validasi data cart_items dan pastikan ada yang dipilih
        $request->validate([
            'cart_items' => 'required|array|min:1',
            'cart_items.*' => 'exists:carts,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:1',
            'unit' => 'required|array',
            'unit.*' => 'in:kg,gram',
        ]);

        $user = Auth::user();
        $totalPrice = 0;

        // Loop melalui item yang dipilih
        foreach ($request->cart_items as $cartId) {
            $cart = Cart::find($cartId);
            $quantity = $request->input('quantity.' . $cartId);
            $unit = $request->input('unit.' . $cartId);

            if ($unit === 'gram') {
                $quantity = $quantity / 1000; // Ubah ke kg jika unit gram
            }

            // Hitung total harga untuk item
            $totalPrice += $cart->product->price * $quantity;
        }

        // Lakukan proses checkout, misalnya menyimpan transaksi, dll.
        return view('user.checkout', compact('totalPrice','addresses','title','count','carts'));
    }


    // Menampilkan halaman sukses
    public function orderSuccess($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        return view('user.order_success', compact('transaction'));
    }
}
