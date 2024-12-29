<?php

namespace App\Http\Controllers\admin;

use App\Models\tpk;
use App\Models\Bobot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\hasil_tpk;

class SpkController extends Controller
{
    public function index()
    {
        // Default kriteria
        $kriteria = ['Jumlah Beli', 'Jumlah Harga', 'Jumlah Berat'];
        $name = 'Tpk';
        $title = 'Tpk';

        // Tampilkan halaman input
        return view('admin.page.tpk.ahp', compact('kriteria', 'name', 'title'));
    }
    public function calculate(Request $request)
    {
        // Validasi input matriks
        $validated = $request->validate([
            'matriks.*.*' => 'required|numeric|min:0',
        ]);

        $kriteria = ['Jumlah Beli', 'Jumlah Harga', 'Jumlah Berat'];

        // Ambil matriks dari input
        $matriks = $request->input('matriks');

        // Normalisasi Matriks
        $totalKolom = array_map(function ($colIndex) use ($matriks) {
            return array_sum(array_column($matriks, $colIndex));
        }, array_keys($matriks[0]));

        $matriksNormalisasi = array_map(function ($row) use ($totalKolom) {
            return array_map(function ($value, $colIndex) use ($totalKolom) {
                return $value / $totalKolom[$colIndex];
            }, $row, array_keys($row));
        }, $matriks);

        // Bobot Kriteria (rata-rata setiap baris matriks normalisasi)
        $bobotKriteria = array_map(function ($row) {
            return array_sum($row) / count($row);
        }, $matriksNormalisasi);

        // Data dari tabel `tpk`
        $dataTPK = tpk::all();
        $name = 'Tpk';
        $title = 'Tpk';

        // Kirim hasil ke view
        return view('admin.page.tpk.result', compact('kriteria', 'matriks', 'matriksNormalisasi', 'bobotKriteria', 'dataTPK', 'name', 'title'));
    }

    public function store(Request $request)
    {
        Bobot::updateOrCreate(
            [],
            [
                'quantity' => $request->input('quantity'),
                'price' => $request->input('price'),
                'transactions' => $request->input('transactions'),
            ]
        );

        return redirect()->route('spk.saw.index')->with('success', 'Bobot berhasil disimpan atau diperbarui.');
    }
    public function SAW(Request $request)
    {
        $name = 'Tpk';
        $title = 'Tpk';
        $data = [];
        $bobot = Bobot::first();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            // Ambil data berdasarkan filter tanggal
            $data = tpk::whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy('user_id') // Kelompokkan berdasarkan user_id
                ->map(function ($rows) {
                    return [
                        'user_id' => $rows->first()->user_id,
                        'username' => $rows->first()->user->username,
                        'quantity' => $rows->sum('quantity'), // Total quantity
                        'price' => $rows->sum('price'), // Total price
                        'transactions' => $rows->sum('transactions'), // Total transactions
                    ];
                })
                ->values(); // Reset index
        }

        return view('admin.page.tpk.saw', compact('data', 'bobot', 'startDate', 'endDate', 'name', 'title'));
    }

    /**
     * Proses SAW.
     */
    public function process(Request $request)
    {
        $name = 'Tpk';
        $title = 'Tpk';
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil dan kelompokkan data
        $data = tpk::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('user_id')
            ->map(function ($rows) {
                return [
                    'user_id' => $rows->first()->user_id,
                    'username' => $rows->first()->user->username,
                    'quantity' => $rows->sum('quantity'),
                    'price' => $rows->sum('price'),
                    'transactions' => $rows->sum('transactions'),
                ];
            })
            ->values();

        // Ambil bobot
        $bobot = Bobot::first();
        if (!$bobot) {
            return redirect()->route('spk.saw.index')->with('error', 'Bobot belum tersedia. Silakan atur bobot terlebih dahulu.');
        }

        // Normalisasi data
        $normalisasi = [];
        $maxValues = [
            'quantity' => $data->max('quantity'),
            'price' => $data->max('price'),
            'transactions' => $data->max('transactions'),
        ];

        foreach ($data as $row) {
            $normalisasi[] = [
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'quantity' => $row['quantity'] / $maxValues['quantity'],
                'price' => $row['price'] / $maxValues['price'],
                'transactions' => $row['transactions'] / $maxValues['transactions'],
            ];
        }

        // Hitung skor akhir
        $results = [];
        foreach ($normalisasi as $row) {
            $results[] = [
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'score' => ($row['quantity'] * $bobot->quantity) +
                    ($row['price'] * $bobot->price) +
                    ($row['transactions'] * $bobot->transactions),
            ];
        }
        usort($results, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return view('admin.page.tpk.proses', compact('results', 'startDate', 'endDate', 'bobot', 'data', 'normalisasi', 'name', 'title'));
    }

    public function sawStore(Request $request)
    {
        $results = $request->input('results');

        // Simpan data hasil SPK ke dalam tabel hasil_tpk
        foreach ($results as $result) {
            hasil_tpk::create([
                'user_id' => $result['user_id'],
                'username' => $result['username'],
                'score' => $result['score'],
            ]);
        }

        return redirect()->route('spk.saw.index')->with('success', 'Hasil SPK berhasil disimpan.');
    }
    public function hasil()
    {
        $name = 'Tpk';
        $title = 'Tpk';

        // Mengambil data berdasarkan tanggal, jam, menit, dan detik
        $hasilTpk = hasil_tpk::selectRaw('created_at as waktu, MAX(score) as max_score')
            ->groupBy('waktu')
            ->orderBy('waktu', 'desc')
            ->get();

        $juara = hasil_tpk::selectRaw('username, created_at as waktu, score')
            ->whereIn('score', function ($query) {
                $query->selectRaw('MAX(score)')
                    ->from('hasil_tpks')
                    ->groupByRaw('created_at');
            })
            ->orderBy('waktu', 'desc')
            ->get();

        return view('admin.page.tpk.hasil', compact('hasilTpk', 'juara', 'name', 'title'));
    }

    // Detail hasil TPK berdasarkan waktu
    public function detail($datetime)
    {
        $name = 'Tpk';
        $title = 'Tpk';

        // Mengambil detail hasil sesuai created_at
        $hasilDetail = hasil_tpk::where('created_at', $datetime)
            ->orderBy('score', 'desc')
            ->get();

        return view('admin.page.tpk.detail', compact('hasilDetail', 'datetime', 'name', 'title'));
    }
}
