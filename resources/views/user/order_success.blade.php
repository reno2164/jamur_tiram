@extends('layouts.user')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
    }

    .success-container {
        width: 80%;
        max-width: 700px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .message {
        margin-bottom: 30px;
    }

    .message .icon {
        font-size: 48px;
        color: #28a745;
        margin-bottom: 10px;
    }

    .message h2 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #333;
    }

    .message p {
        font-size: 16px;
        color: #555;
    }

    .order-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        border: 1px dashed #ccc;
        padding: 15px;
        border-radius: 8px;
        background-color: #f8f8f8;
    }

    .info-box p {
        margin: 5px 0;
        font-size: 14px;
        color: #555;
    }

    .info-box strong {
        font-size: 14px;
        color: #333;
    }

    .order-details {
        text-align: left;
        margin-top: 20px;
    }

    .order-details h3 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    table th,
    table td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    table .total-row td {
        font-weight: bold;
        color: #333;
    }
</style>
<div class="success-container">
    <h1 class="title">PESANAN SUKSES</h1>
    <div class="message">
        <div class="icon">
            <span>✔</span>
        </div>
        <h2>Pesanan Anda Sedang Di Proses</h2>
        <p>Terima kasih. Pesanan Anda telah diterima.</p>
    </div>
    <div class="order-info">
        <div class="info-box">
            <p><strong>Kode Transaksi</strong></p>
            <p>{{ $transaction->transaction_code }}</p>
        </div>
        <div class="info-box">
            <p><strong>Tanggal</strong></p>
            <p>{{ $transaction->created_at->format('d-m-Y') }}</p>
        </div>
        <div class="info-box">
            <p><strong>Total</strong></p>
            <p>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
        </div>
        <div class="info-box">
            <p><strong>Metode Pembayaran</strong></p>
            <p>{{ ucfirst($transaction->payment_method) }}</p>
        </div>
    </div>
    <div class="order-details">
        <h3>DETAIL PESANAN</h3>
        <table>
            <thead>
                <tr>
                    <th>PRODUK</th>
                    <th>SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->transactionDetails as $detail)
                    <tr>
                        <td>
                            {{ $detail->product->title }} x 
                                {{ $detail->quantity }} kg
                        </td>
                        <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total</td>
                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                </tr>
            </tbody>            
        </table>
        <div class="d-flex justify-content-end">
            <a href="{{ route('home') }}" class="btn btn-success btn-end">Kembali Ke Home</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Coba Lagi'
        });
    </script>
@endif
@endsection
