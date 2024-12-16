<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class SAWController extends Controller
{
    public function saw(Request $request)
    {
        // Filter berdasarkan tanggal
        $start_date = $request->get('start_date', now()->startOfMonth());
        $end_date = $request->get('end_date', now()->endOfMonth());
    
        // Ambil data pengguna dan transaksi selesai
        $tpk_data = User::with(['transactions' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date])
                  ->where('status', 'selesai'); // Hanya transaksi selesai
        }])
        ->get()
        ->filter(function ($user) {
            return $user->transactions->isNotEmpty(); // Hanya ambil pengguna yang memiliki transaksi selesai
        });
    
        // Hitung jumlah pembelian (berapa kali akun membeli dalam rentang waktu) serta total harga dan berat
        foreach ($tpk_data as $user) {
            $user->pembelian_bulan = $user->transactions->count(); // Jumlah transaksi selesai
            $user->total_price = $user->transactions->sum('total_price'); // Total harga dari transaksi selesai
            $user->total_weight = $user->transactions->flatMap->transactionDetails->sum('quantity'); // Berat total dari transaksi selesai
        }
    
        $sorted_data = collect(); // Variabel untuk hasil perhitungan SAW
        $bobot_ahp = $this->calculateAHP(); // Ambil bobot dari metode calculateAHP
    
        // Hanya proses perhitungan jika tombol Proses diklik
        if ($request->isMethod('post')) {
            // Bobot kriteria dari AHP
            $bobot_harga = $bobot_ahp['harga'];
            $bobot_berat = $bobot_ahp['berat'];
            $bobot_pembelian_bulan = $bobot_ahp['pembelian'];
    
            // Perhitungan TPK dengan SAW
            foreach ($tpk_data as $user) {
                $user->tpk = ($user->total_price * $bobot_harga) 
                           + ($user->total_weight * $bobot_berat) 
                           + ($user->pembelian_bulan * $bobot_pembelian_bulan);
            }
    
            // Urutkan berdasarkan nilai TPK (desc) untuk tabel peringkat
            $sorted_data = $tpk_data->sortByDesc('tpk')->values();
        }
    
        // Kirim data ke view
        $title = 'tpk';
        $name = 'tpk';
        return view('admin.saw', compact('tpk_data', 'sorted_data', 'title', 'name', 'bobot_ahp'));
    }

    // Metode untuk menghitung bobot AHP
    public function calculateAHP()
    {
        // Matriks perbandingan berpasangan
        $matriks = [
            [1, 1, 3],       // Harga
            [1, 1, 2],       // Berat
            [1/3, 1/2, 1],   // Pembelian dalam Sebulan
        ];

        // Total kolom
        $totalKolom = array_map(function ($index) use ($matriks) {
            return array_sum(array_column($matriks, $index));
        }, array_keys($matriks[0]));

        // Normalisasi matriks
        $normalisasi = array_map(function ($row) use ($totalKolom) {
            return array_map(function ($value, $total) {
                return $value / $total;
            }, $row, $totalKolom);
        }, $matriks);

        // Hitung nilai eigen vector (bobot)
        $eigenVector = array_map(function ($row) {
            return array_sum($row) / count($row);
        }, $normalisasi);

        // Simpan bobot ke dalam array
        return [
            'harga' => $eigenVector[0],
            'berat' => $eigenVector[1],
            'pembelian' => $eigenVector[2],
        ];
    }
}
