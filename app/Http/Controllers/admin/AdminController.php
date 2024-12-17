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
    public function tpk(Request $request)
    {
        $results = collect(); // Default kosong

        // Jika filter tanggal diterapkan
        if ($request->has(['start_date', 'end_date'])) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Query untuk mengambil data berdasarkan filter tanggal
            $results = Transaction::query()
            ->selectRaw('users.username, SUM(transaction_details.quantity) as total_quantity, SUM(transactions.total_price) as total_price, COUNT(transactions.id) as total_transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.status', 'selesai') // Filter status selesai
            ->groupBy('users.id', 'users.username')
            ->orderByDesc('total_price') // Mengurutkan berdasarkan total harga
            ->get();
        }
        $name = 'Tpk';
        $title = 'Tpk';
        return view('admin.page.tpk', compact('results', 'title', 'name'));
    }
}
