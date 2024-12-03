@extends('admin.layout.index')

@section('content')
<div class="container mt-5">
    <h2>Detail Pesanan</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Kode Transaksi: {{ $order->transaction_code }}</h5>
            <p class="card-text"><strong>Nama Pemesan:</strong> {{ $order->user->username }}</p>
            <p class="card-text"><strong>Email Pemesan:</strong> {{ $order->user->email }}</p>
            <p class="card-text"><strong>Alamat Pengiriman:</strong> {{ $order->address->address }}</p>
            <p class="card-text"><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
            <p class="card-text"><strong>Total Harga:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $order->status }}</p>
        </div>
    </div>

    <h4>Daftar Produk</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->transactionDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->product->title }}</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.datapenjualan') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
