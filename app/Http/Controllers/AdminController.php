<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Pastikan model ini sesuai dengan database Anda

class AdminController extends Controller
{
    // Metode untuk halaman SAW
    public function saw()
    {
        $title = 'SAW - Sistem Pendukung Keputusan';
        return view('admin.saw', compact('title'));
    }

    // Metode untuk halaman TPK
    public function tpk()
    {
        // Ambil data awal untuk halaman TPK (misal data produk atau transaksi)
        $products = Product::all(); // Mengambil data produk dari database
        return view('admin.tpk.index', compact('products'));
    }

    // Proses bobot dan hitungan dengan metode SAW
    public function prosesTpk(Request $request)
    {
        // Ambil data bobot dari form input
        $priceWeight = $request->input('priceWeight');
        $weightWeight = $request->input('weightWeight');
        $purchaseWeight = $request->input('purchaseWeight');

        // Ambil data produk dari database
        $products = Product::all();

        // Proses perhitungan menggunakan SAW
        foreach ($products as $product) {
            // Contoh penghitungan nilai SAW (berdasarkan bobot yang dimasukkan pengguna)
            $product->score = ($product->price * $priceWeight) +
                              ($product->weight * $weightWeight) +
                              ($product->purchase * $purchaseWeight);
        }

        // Urutkan produk berdasarkan skor secara menurun
        $rankedProducts = $products->sortByDesc('score');

        // Kirim data ke halaman hasil
        return view('admin.tpk.hasil', ['products' => $rankedProducts]);
    }

    // Tampilkan hasil perhitungan TPK
    public function hasilTpk()
    {
        // Ambil hasil perhitungan TPK dari database
        $hasil = []; // Contoh: data dummy
        return view('admin.tpk.hasil', compact('hasil'));
    }

    // Tampilkan detail hasil TPK berdasarkan ID
    public function detailHasilTpk($id)
    {
        // Ambil detail hasil berdasarkan ID
        $detail = []; // Contoh: data dummy
        return view('admin.tpk.detail', compact('detail'));
    }
}
