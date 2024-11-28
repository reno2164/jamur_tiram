<?php

namespace App\Http\Controllers\admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.page.dashboard', [
            'name' => 'Dashboard',
            'title' => 'Admin'
        ]);
    }
    public function Pesanan()
    {
        $name = 'Pesanan';
        $title = 'Pesanan';
        $allOrders = Transaction::where('status', '!=', 'Selesai')->get();
        $unpaidOrders = Transaction::with('user')->where('status', 'Belum Dibayar')->get();
        $packagingOrders = Transaction::with('user')->where('status', 'Sedang Dikemas')->get();

        return view('admin.page.pesanan', compact('allOrders', 'unpaidOrders', 'packagingOrders', 'name', 'title'));
    }
    public function show($id)
    {
        $name = 'Detail Pesanan';
        $title = 'Detail Pesanan';
        $order = Transaction::with(['transactionDetails.product', 'user', 'address'])->findOrFail($id);
        return view('admin.page.detailPesanan', compact('order', 'name', 'title'));
    }

    public function updateStatus($id)
    {
        // Ambil transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($id);

        // Periksa apakah status saat ini adalah "Sedang Dikemas"
        if ($transaction->status === 'Sedang Dikemas') {
            // Ubah status menjadi "Selesai"
            $transaction->status = 'Selesai';
            $transaction->save();

            return redirect()->route('admin.datapenjualan')
                ->with('success', 'Status pesanan berhasil diperbarui menjadi Selesai.');
        }

        return redirect()->route('admin.pesanan')
            ->with('error', 'Status pesanan tidak dapat diubah.');
    }
    public function dataPenjualan(Request $request)
    {
        $name = 'Data Penjualan';
        $title = 'Data Penjualan';
        $query = Transaction::where('status', 'Selesai');

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        $completedOrders = $query->with('user')->get();

        return view('admin.page.datapenjualan', compact('completedOrders', 'title', 'name'));
    }
    public function showDataPenjualan($id)
    {
        $name = 'Data Penjualan';
        $title = 'Data Penjualan';
        $order = Transaction::with(['transactionDetails.product', 'user', 'address'])->findOrFail($id);
        return view('admin.page.detailDataPenjualan', compact('order', 'title', 'name'));
    }
    public function downloadPdf(Request $request)
    {
        $query = Transaction::where('status', 'Selesai');

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $completedOrders = $query->with('user','transactionDetails.product')->get();

        $pdf = Pdf::loadView('admin.page.downloadPDF', compact('completedOrders'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_pesanan_selesai.pdf');
    }
}
