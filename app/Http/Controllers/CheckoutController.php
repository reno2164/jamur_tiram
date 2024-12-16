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
            'transaction_code' => 'TRX-' . now()->format('Ymd') . '-' . $user->id . '-' . strtoupper(Str::random(6)),
            'total_price' => $totalPrice,
            'payment_method' => $validatedData['payment_method'],
            'status' => $validatedData['payment_method'] === 'cod' ? 'Sedang Dikemas' : 'Belum Dibayar', 
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
            Config::$isProduction = config('midtrans.isProduction');
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
                $transaction->update(['status' => 'Belum Dibayar']);

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

    public function handlePaymentNotification(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            // Validasi notifikasi dari Midtrans
            $notification = new Notification();

            // Ambil data notifikasi
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id; // Transaction Code
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status;

            // Cari transaksi berdasarkan kode transaksi
            $transaction = Transaction::where('transaction_code', $orderId)->first();

            if (!$transaction) {
                Log::error('Transaksi dengan kode ' . $orderId . ' tidak ditemukan.');
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            // Update status transaksi berdasarkan status pembayaran dari Midtrans
            if ($transactionStatus === 'capture') {
                if ($paymentType === 'credit_card') {
                    $transaction->status = ($fraudStatus === 'challenge') ? 'Menunggu Verifikasi' : 'Sedang Dikemas';
                } else {
                    $transaction->status = 'Sedang Dikemas';
                }
            } elseif ($transactionStatus === 'settlement') {
                $transaction->status = 'Sedang Dikemas';
            } elseif ($transactionStatus === 'pending') {
                $transaction->status = 'Belum Dibayar';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                foreach ($transaction->transactionDetails as $detail) {
                    $product = $detail->product;
    
                    // Mengupdate stok produk
                    $product->stok += $detail->quantity;
                    $product->save();
                }
                $transaction->transactionDetails()->delete();
                // Hapus transaksi
                $transaction->delete();
            } else {
                Log::warning('Status pembayaran tidak dikenali: ' . $transactionStatus);
                foreach ($transaction->transactionDetails as $detail) {
                    $product = $detail->product;
    
                    // Mengupdate stok produk
                    $product->stok += $detail->quantity;
                    $product->save();
                }
                $transaction->transactionDetails()->delete();
                // Hapus transaksi
                $transaction->delete();
            }

            $transaction->save();

            Log::info('Status transaksi ' . $orderId . ' berhasil diperbarui: ' . $transaction->status);
            return response()->json(['message' => 'Notifikasi pembayaran berhasil diproses'], 200);
        } catch (\Exception $e) {
            Log::error('Error handling Midtrans notification: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }


    public function pay($transaction_code)
    {
        $transaction = Transaction::where('transaction_code', $transaction_code)->firstOrFail();

        // Pastikan status transaksi adalah "Belum Dibayar"
        if ($transaction->status !== 'Belum Dibayar') {
            return redirect()->route('riwayat')->withErrors(['payment' => 'Transaksi ini tidak valid untuk pembayaran ulang.']);
        }

        // Generate ulang Snap Token
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
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
                $pricePerGram = intval($detail->product->price / 1000);
                    $quantityInGrams = intval($detail->quantity * 1000);
                return [
                    'id' => $detail->product_id,
                    'price' => $pricePerGram,
                    'quantity' => $quantityInGrams,
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
            return redirect()->route('pesanan.index')->withErrors(['payment' => 'Gagal membuat pembayaran ulang.']);
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
