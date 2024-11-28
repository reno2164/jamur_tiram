<?php

namespace App\Http\Controllers\admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
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
        $transactions = Transaction::with(['user', 'transactionDetails.product', 'address'])->get();
        return view('admin.page.pesanan', compact('transactions','name','title'));
    }
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:Pesanan Disiapkan,Berhasil,Gagal',
        ]);

        // Cari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($id);

        // Perbarui status transaksi
        $transaction->update([
            'status' => $request->status,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
