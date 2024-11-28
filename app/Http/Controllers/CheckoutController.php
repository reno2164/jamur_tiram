<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\Cart;
use Midtrans\Config;
use App\Models\Address;
use App\Models\Product;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // Menampilkan halaman checkout
    public function index()
    {
        $selectedAddress = Auth::user()->addresses()->where('is_default', true)->first();

        if (!$selectedAddress) {
            $selectedAddress = Auth::user()->addresses()->first(); // Pilih alamat pertama jika tidak ada alamat utama
        }
        $title = 'checkout';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)->get();
        $addresses = Address::where('user_id', $user->id)->get();

        // Hitung total harga langsung dari data keranjang
        $cartItems = $carts->pluck('id')->toArray(); // Ambil ID cart
        $quantities = $carts->pluck('quantity', 'id')->toArray(); // Ambil jumlah
        $units = array_fill_keys($cartItems, 'kg'); // Default semua ke 'kg'

        $totalPrice = $this->calculateTotalPrice($cartItems, $quantities, $units);

        return view('user.checkout', compact('carts', 'addresses', 'title', 'count', 'totalPrice', 'selectedAddress'));
    }


    // Menangani proses checkout
    public function checkout(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validatedData = $request->validate([
            'payment_method' => 'required|in:bank_transfer,cod',
        ]);

        // Pastikan user memiliki alamat terpilih
        $selectedAddress = $user->addresses()->first();
        if (!$selectedAddress) {
            return redirect()->back()->withErrors(['address' => 'Alamat pengiriman belum dipilih.']);
        }

        // Ambil keranjang user
        $carts = Cart::where('user_id', $user->id)->with('product')->get();

        foreach ($carts as $cart) {
            $product = $cart->product;
            if ($cart->quantity > $product->stok) {
                return redirect()->back()->withErrors([
                    'stock' => "Stok untuk produk {$product->title} tidak mencukupi.",
                ]);
            }
        }

        if ($carts->isEmpty()) {
            return redirect()->back()->withErrors(['cart' => 'Keranjang Anda kosong.']);
        }

        $totalPrice = $carts->sum('price');


        // Simpan transaksi
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'transaction_code' => strtoupper(Str::random(10)), // Buat kode transaksi unik
            'total_price' => $totalPrice,
            'payment_method' => $validatedData['payment_method'],
            'status' => $validatedData['payment_method'] === 'cod' ? 'Pesanan Disiapkan' : 'Menunggu Pembayaran', // Status default
            'address_id' => $selectedAddress->id,
        ]);
        // Simpan detail transaksi dan perbarui stok produk
        foreach ($carts as $cart) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->price,
            ]);

            // Kurangi stok produk
            $product = Product::find($cart->product_id);
            if ($product) {
                $product->stok -= $cart->quantity;
                $product->qty_out += $cart->quantity;
                $product->save();
            }
        }

        // Hapus keranjang setelah transaksi selesai
        Cart::where('user_id', $user->id)->delete();
        // Periksa metode pembayaran
        if ($validatedData['payment_method'] === 'bank_transfer') {
            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Data untuk Midtrans
            $midtransParams = [
                'transaction_details' => [
                    'order_id' => $transaction->transaction_code,
                    'gross_amount' => $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $user->username,
                    'email' => $user->email,
                ],
                'item_details' => $carts->map(function ($cart) {
                    $pricePerGram = intval($cart->product->price / 1000);
                    $quantityInGrams = intval($cart->quantity * 1000);

                    return [
                        'id' => (string) $cart->product_id,
                        'price' => $pricePerGram,
                        'quantity' => $quantityInGrams,
                        'name' => $cart->product->title,
                    ];
                })->toArray(),

            ];

            try {
                $snapToken = Snap::getSnapToken($midtransParams);
                $title = 'checkout';
                $count = Auth::check() ? Auth::user()->carts->count() : 0;
                // Set status transaksi menjadi 'Menunggu Pembayaran'
                $transaction->update(['status' => 'Menunggu Pembayaran']);

                return view('user.payment', compact('snapToken', 'transaction', 'title', 'count'));
            } catch (\Exception $e) {
                return redirect()->back()->withErrors([
                    'payment' => 'Terjadi kesalahan dalam memproses pembayaran. Coba lagi nanti.',
                ]);
            }
        } else {
            return redirect()->route('checkout.success', $transaction->transaction_code)->with('success', 'Pesanan Anda berhasil dibuat!');
        }
    }

    public function handleNotification(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        if ($notification->transaction_status === 'settlement') {
            Transaction::where('transaction_code', $notification->order_id)
                ->update(['status' => 'Pesanan Disiapkan']);
        }

        return response()->json(['message' => 'Notification received successfully']);
    }
    public function handlePaymentNotification(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = false; // Ubah ke true jika di production
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id; // Transaction Code
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status;

            // Cari transaksi berdasarkan kode transaksi
            $transaction = Transaction::where('transaction_code', $orderId)->first();

            if (!$transaction) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            // Update status transaksi berdasarkan status pembayaran dari Midtrans
            if ($transactionStatus === 'capture') {
                if ($paymentType === 'credit_card') {
                    if ($fraudStatus === 'challenge') {
                        $transaction->status = 'Menunggu Verifikasi';
                    } else {
                        $transaction->status = 'Pesanan Disiapkan'; // Berhasil
                    }
                }
            } elseif ($transactionStatus === 'settlement') {
                $transaction->status = 'Pesanan Disiapkan'; // Pembayaran selesai
            } elseif ($transactionStatus === 'pending') {
                $transaction->status = 'Menunggu Pembayaran';
            } elseif ($transactionStatus === 'deny' || $transactionStatus === 'expire' || $transactionStatus === 'cancel') {
                $transaction->status = 'Gagal';
            }

            $transaction->save();

            return response()->json(['message' => 'Notifikasi pembayaran berhasil diproses'], 200);
        } catch (\Exception $e) {
            Log::error('Error handling Midtrans notification: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function pay($transaction_code)
    {
        $transaction = Transaction::where('transaction_code', $transaction_code)->firstOrFail();

        // Pastikan status transaksi adalah "Menunggu Pembayaran"
        if ($transaction->status !== 'Menunggu Pembayaran') {
            return redirect()->route('riwayat')->withErrors(['payment' => 'Transaksi ini tidak valid untuk pembayaran ulang.']);
        }

        // Generate ulang Snap Token
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $midtransParams = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_code,
                'gross_amount' => $transaction->total_price,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->username,
                'email' => $transaction->user->email,
            ],
            'item_details' => $transaction->transactionDetails->map(function ($detail) {
                return [
                    'id' => $detail->product_id,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'name' => $detail->product->title,
                ];
            })->toArray(),
        ];

        try {
            $snapToken = Snap::getSnapToken($midtransParams);
            $title = 'checkout';
            $count = Auth::check() ? Auth::user()->carts->count() : 0;
            return view('user.payment', compact('snapToken', 'transaction', 'title', 'count'));
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect()->route('riwayat')->withErrors(['payment' => 'Gagal membuat pembayaran ulang.']);
        }
    }

    private function calculateTotalPrice($cartItems, $quantities, $units)
    {
        $totalPrice = 0;

        foreach ($cartItems as $cartId) {
            $cart = Cart::find($cartId);
            $quantity = $quantities[$cartId];
            $unit = $units[$cartId];

            if ($unit === 'gram') {
                $quantity = $quantity / 1000; // Ubah ke kg jika unit gram
            }

            $totalPrice += $cart->product->price * $quantity;
        }

        return $totalPrice;
    }
    public function success($transaction_code)
    {
        // Cari transaksi berdasarkan kode transaksi
        $transaction = Transaction::with(['transactionDetails.product', 'address'])
            ->where('transaction_code', $transaction_code)
            ->firstOrFail();

        $title = 'Checkout Sukses';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;

        return view('user.order_success', compact('title', 'count', 'transaction'));
    }
}
