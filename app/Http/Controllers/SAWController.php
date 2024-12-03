<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; 
use App\Models\User; // Import model User

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
    
        // Hanya proses perhitungan jika tombol Proses diklik
        if ($request->isMethod('post')) {
            // Bobot kriteria dari input form
            $bobot_harga = $request->input('bobot_harga', 0.4429);
            $bobot_berat = $request->input('bobot_berat', 0.3873);
            $bobot_pembelian_bulan = $request->input('bobot_pembelian_bulan', 0.1698);
    
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
        return view('admin.saw', compact('tpk_data', 'sorted_data', 'title', 'name'));
    }
}    