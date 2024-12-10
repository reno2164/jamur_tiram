<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Midtrans\Config;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Transaction as MidtransTransaction;


class UserController extends Controller
{
    public function index()
    {
        $data = Product::latest()->paginate(10);
        $best = product::where('qty_out', '>=', 25)->get();
        $countKeranjang = Auth::check() ? Auth::user()->carts->count() : 0;

        return view('user.index', [
            'title' => 'home',
            'data' => $data,
            'best' => $best,
            'count' => $countKeranjang
        ]);
    }
    public function contact()
    {
        $title = 'Detail Produk';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;

        // Pass product data to the view
        return view('user.contact', compact('title', 'count'));
    }
    public function detailProduk(string $id)
    {
        $product = Product::findOrFail($id);
        if ($product->stok == 0) {
            return redirect()->back()->with('error', 'Stok produk ini habis');
        }
        $title = 'Detail Produk';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        $isInCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        // Pass product data to the view
        return view('user.detailproduk', compact('product', 'title', 'isInCart', 'count'));
    }

    public function addTocart(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // Pastikan product_id valid
            'quantity' => 'required|numeric|min:0.001', // Minimal 0.001 kg
            'unit' => 'required|in:kg,gram', // Validasi unit harus 'kg' atau 'gram'
        ]);

        $userId = Auth::id(); // Ambil ID pengguna yang sedang login

        // Ambil data produk berdasarkan ID
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Konversi quantity ke kilogram jika unit adalah gram
        $quantity = $validated['quantity'];
        if ($validated['unit'] === 'gram') {
            $quantity = $quantity / 1000; // Konversi gram ke kg
        }

        // Validasi stok
        if ($quantity > $product->stok) {
            return redirect()->back()->with('error', 'Jumlah yang dimasukkan melebihi stok produk.');
        }

        // Tambahkan atau perbarui data di tabel cart
        $cart = Cart::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
            ],
            [
                'quantity' => $quantity,
                'price' => $product->price * $quantity, // Hitung harga berdasarkan quantity
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

    public function showCart()
    {
        $title = 'Keranjang';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        // Ambil produk dalam keranjang berdasarkan pengguna yang sedang login
        $carts = Cart::where('user_id', Auth::id())->get();
        // $stok = $carts->product->stok;


        return view('user.cart', compact('carts', 'title', 'count'));
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
    public function riwayat()
    {
        $title = 'Riwayat Pesanan';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        // Ambil transaksi milik user yang sedang login
        $transactions = Transaction::where('user_id', Auth::id())
            ->with(['transactionDetails.product', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Tampilkan halaman riwayat transaksi
        return view('user.riwayat', compact('transactions', 'title', 'count'));
    }
    public function pesanan()
    {
        $title = 'Pesanan';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        $orders = Transaction::where('user_id', Auth::id())->get();
        return view('user.pesanan', compact('orders', 'title', 'count'));
    }
    public function show($transaction_code)
    {
        $title = 'Deatil Pesanan';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        $order = Transaction::with(['transactionDetails.product', 'address'])
            ->where('transaction_code', $transaction_code)
            ->firstOrFail();

        // Pastikan hanya user terkait yang dapat melihat detail pesanan
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        return view('user.detailPesanan', compact('order', 'title', 'count'));
    }
    public function cancel($transaction_id)
{
    // Mencari transaksi berdasarkan ID
    $transaction = Transaction::findOrFail($transaction_id);

    // Pastikan status transaksi masih "Belum Dibayar" atau "Sedang Dikemas"
    if ($transaction->status === 'Belum Dibayar' || $transaction->status === 'Sedang Dikemas') {
        // Jika metode pembayaran adalah transfer, cek status transaksi di Midtrans
        if ($transaction->payment_method === 'bank_transfer') {
            // Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            try {
                // Periksa status transaksi di Midtrans
                $midtransStatus = MidtransTransaction::status($transaction->transaction_code);

                // Jika status transaksi 'settlement', lakukan refund
                if ($midtransStatus->transaction_status === 'settlement') {
                    // Definisikan parameter refund
                    $refundParams = [
                        'refund_amount' => $transaction->total_price,  // Sesuaikan dengan jumlah total transaksi
                    ];

                    // Panggil API Midtrans untuk melakukan refund
                    $midtransResponse = MidtransTransaction::refund($transaction->transaction_code, $refundParams);

                    // Jika gagal melakukan refund, kembalikan error
                    if ($midtransResponse->status_code !== '200') {
                        return redirect()->route('pesanan.index')
                            ->with('error', 'Gagal melakukan refund di Midtrans: ' . $midtransResponse->status_message);
                    }

                    // Jika refund berhasil, perbarui status transaksi
                    $transaction->status = 'Refunded'; // Pastikan status transaksi diperbarui
                    $transaction->save();
                } 
                // Jika status transaksi belum diproses (pending, authorize), lakukan pembatalan
                else if (in_array($midtransStatus->transaction_status, ['pending', 'authorize'])) {
                    $midtransResponse = MidtransTransaction::cancel($transaction->transaction_code);

                    // Jika gagal membatalkan di Midtrans, kembalikan error
                    if ($midtransResponse->status_code !== '200') {
                        return redirect()->route('pesanan.index')
                            ->with('error', 'Gagal membatalkan transaksi di Midtrans: ' . $midtransResponse->status_message);
                    }

                    // Jika transaksi berhasil dibatalkan, perbarui status transaksi
                    $transaction->status = 'Cancelled'; // Pastikan status transaksi diperbarui
                    $transaction->save();
                } else {
                    // Jika status transaksi tidak memungkinkan dibatalkan
                    return redirect()->route('pesanan.index')
                        ->with('error', 'Transaksi tidak dapat dibatalkan karena statusnya: ' . ucfirst($midtransStatus->transaction_status) . '. Hubungi admin untuk bantuan lebih lanjut.');
                }
            } catch (\Exception $e) {
                return redirect()->route('pesanan.index')
                    ->with('error', 'Terjadi kesalahan saat membatalkan transaksi di Midtrans: ' . $e->getMessage());
            }
        }

        // Mengembalikan stok produk yang ada di detail transaksi
        foreach ($transaction->transactionDetails as $detail) {
            $product = $detail->product;

            // Mengupdate stok produk
            $product->stok += $detail->quantity;
            $product->save();
        }

        // Hapus detail transaksi terkait
        $transaction->transactionDetails()->delete();

        // Hapus transaksi
        $transaction->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // Jika status sudah tidak memungkinkan untuk dibatalkan
    return redirect()->route('pesanan.index')->with('error', 'Pesanan tidak dapat dibatalkan.');
}

}
