@extends('admin.layout.index')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Detail Pesanan</h1>
    
    <!-- Informasi Transaksi -->
    <div class="card mb-4">
        <div class="card-header">
            Informasi Pesanan
        </div>
        <div class="card-body">
            <p><strong>Kode Transaksi:</strong> {{ $order->transaction_code }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Total Harga:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
        </div>
    </div>

    <!-- Informasi Pelanggan -->
    <div class="card mb-4">
        <div class="card-header">
            Informasi Pelanggan
        </div>
        <div class="card-body">
            <p><strong>Nama Pelanggan:</strong> {{ $order->user->username }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
        </div>
    </div>

    <!-- Informasi Alamat -->
    <div class="card mb-4">
        <div class="card-header">
            Alamat Pengiriman
        </div>
        <div class="card-body">
            <p><strong>Nama Penerima:</strong> {{ $order->address->name }}</p>
            <p><strong>No Telepon:</strong> {{ $order->address->phone_number }}</p>
            <p><strong>Alamat:</strong> {{ $order->address->address }}</p>
        </div>
    </div>

    <!-- Daftar Produk -->
    <div class="card">
        <div class="card-header">
            Daftar Produk
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Kuantitas</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->transactionDetails as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->product->title }}</td>
                            <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="my-4">
        <a href="{{ route('admin.pesanan') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
