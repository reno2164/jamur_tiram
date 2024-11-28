<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SAWController extends Controller
{
    public function index()
    {
        // Data kriteria (dari tabel Anda)
        $data_kriteria = [
            ["No" => 1, "Nama" => "Indra", "Kode" => "C1", "Berat" => 2500, "Harga" => 50000, "Pembelian" => 3],
            ["No" => 2, "Nama" => "Bu Wati", "Kode" => "C2", "Berat" => 200, "Harga" => 4000, "Pembelian" => 3],
            ["No" => 3, "Nama" => "Hari", "Kode" => "C3", "Berat" => 4300, "Harga" => 80000, "Pembelian" => 3],
            ["No" => 4, "Nama" => "Mulyo", "Kode" => "C4", "Berat" => 1000, "Harga" => 20000, "Pembelian" => 1],
            ["No" => 5, "Nama" => "Joko", "Kode" => "C5", "Berat" => 300, "Harga" => 6000, "Pembelian" => 1],
            ["No" => 6, "Nama" => "Lestari", "Kode" => "C6", "Berat" => 2000, "Harga" => 40000, "Pembelian" => 3],
            ["No" => 7, "Nama" => "Upik", "Kode" => "C7", "Berat" => 2000, "Harga" => 40000, "Pembelian" => 1],
            ["No" => 8, "Nama" => "Gina", "Kode" => "C8", "Berat" => 1000, "Harga" => 20000, "Pembelian" => 2],
            ["No" => 9, "Nama" => "Eko", "Kode" => "C9", "Berat" => 4000, "Harga" => 80000, "Pembelian" => 3],
            ["No" => 10, "Nama" => "Ijam", "Kode" => "C10", "Berat" => 1000, "Harga" => 20000, "Pembelian" => 3],
            ["No" => 11, "Nama" => "Surya", "Kode" => "C11", "Berat" => 2000, "Harga" => 40000, "Pembelian" => 2],
            ["No" => 12, "Nama" => "Ratna", "Kode" => "C12", "Berat" => 1000, "Harga" => 20000, "Pembelian" => 3],
            ["No" => 13, "Nama" => "Ida", "Kode" => "C13", "Berat" => 600, "Harga" => 12000, "Pembelian" => 2],
            ["No" => 14, "Nama" => "Ahmad", "Kode" => "C14", "Berat" => 1000, "Harga" => 20000, "Pembelian" => 2],
            ["No" => 15, "Nama" => "Sri", "Kode" => "C15", "Berat" => 800, "Harga" => 16000, "Pembelian" => 1],
            ["No" => 16, "Nama" => "Kartini", "Kode" => "C16", "Berat" => 1000, "Harga" => 20000, "Pembelian" => 2],
            ["No" => 17, "Nama" => "Siti", "Kode" => "C17", "Berat" => 2000, "Harga" => 40000, "Pembelian" => 3],
            ["No" => 18, "Nama" => "Tuti", "Kode" => "C18", "Berat" => 1000, "Harga" => 20000, "Pembelian" => 2],
            ["No" => 19, "Nama" => "Budi", "Kode" => "C19", "Berat" => 2000, "Harga" => 40000, "Pembelian" => 1],
            ["No" => 20, "Nama" => "Bambang", "Kode" => "C20", "Berat" => 1000, "Harga" => 20000, "Pembelian" => 3],
        ];

        // Bobot kriteria
        $weights = [0.4429, 0.3873, 0.1698]; // Bobot: Berat, Harga, Pembelian

        // Cari nilai maksimum untuk setiap kriteria
        $max_berat = max(array_column($data_kriteria, "Berat"));
        $max_harga = max(array_column($data_kriteria, "Harga"));
        $max_pembelian = max(array_column($data_kriteria, "Pembelian"));

        // Hitung skor SAW untuk setiap alternatif
        foreach ($data_kriteria as $key => $data) {
            $data_kriteria[$key]['Skor'] = (
                ($data['Berat'] / $max_berat) * $weights[0] +
                ($data['Harga'] / $max_harga) * $weights[1] +
                ($data['Pembelian'] / $max_pembelian) * $weights[2]
            );
        }

        // Cari alternatif terbaik (skor tertinggi)
        $alternatif_terbaik = collect($data_kriteria)->sortByDesc('Skor')->first();

        // Kirim data ke view
        return view('admin.page.products.saw_result', [
            'data_kriteria' => $data_kriteria,
            'alternatif_terbaik' => $alternatif_terbaik
        ]);
    }
}
