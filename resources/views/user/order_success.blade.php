@extends('layouts.user')

@section('content')
    <div class="container">
        <h1>Pesanan Berhasil</h1>
        <p>Terima kasih telah melakukan pembelian. Kode Transaksi Anda adalah <strong>{{ $transaction->transaction_code }}</strong>.</p>
        <p>Total Pembayaran: <strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></p>

        <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
@endsection
