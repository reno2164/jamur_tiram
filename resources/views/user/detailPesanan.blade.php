@extends('layouts.user')

@section('content')
    <style>
        .order-detail-container {
            margin: 30px auto;
            max-width: 1200px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .order-header h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .order-header p {
            font-size: 14px;
            color: #666;
        }

        .order-info {
            margin-bottom: 20px;
        }

        .order-info .info {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .order-info .info strong {
            color: #333;
        }

        .product-list {
            margin-top: 20px;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #ddd;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }

        .product-details {
            flex: 1;
            margin-left: 20px;
        }

        .product-details .title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .product-details .price {
            font-size: 14px;
            color: #666;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .summary .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary .row:last-child {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
        }

        .status-badge.belum-dibayar {
            background-color: #ff9800;
        }

        .status-badge.sedang-dikemas {
            background-color: #2196f3;
        }

        .status-badge.selesai {
            background-color: #4caf50;
        }
        .btn-cancel {
            background-color: #f44336;
            color: #ffffff;
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background-color: #e53935;
            color: #ffffff;
        }
    </style>

    <div class="order-detail-container">
        <div class="order-header">
            <h2>Detail Pesanan</h2>
            <p>Kode Pesanan: <strong>{{ $order->transaction_code }}</strong></p>
            <p>Status Pesanan: 
                <span class="status-badge {{ strtolower(str_replace(' ', '-', $order->status)) }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
        </div>

        <div class="order-info">
            <div class="info">
                <strong>Nama Penerima:</strong> {{ $order->address->name }}
            </div>
            <div class="info">
                <strong>Alamat Pengiriman:</strong> {{ $order->address->address }}
            </div>
            <div class="info">
                <strong>Tanggal Pemesanan:</strong> {{ $order->created_at->format('d M Y, H:i') }}
            </div>
        </div>

        <div class="product-list">
            <h4>Produk</h4>
            @foreach ($order->transactionDetails as $detail)
                <div class="product-item">
                    <img src="{{ asset('storage/products/' . $detail->product->image) }}" alt="{{ $detail->product->title }}">
                    <div class="product-details">
                        <p class="title">{{ $detail->product->title }}</p>
                        <p class="price">Rp{{ number_format($detail->product->price, 0, ',', '.') }} x {{ $detail->quantity }}</p>
                    </div>
                    <div class="subtotal">
                        <strong>Rp{{ number_format($detail->price, 0, ',', '.') }}</strong>
                    </div>
                </div>
            @endforeach
            
        </div>

        <div class="summary">
            <div class="row">
                <span>Subtotal</span>
                <span>Rp{{ number_format($order->transactionDetails->sum(fn($detail) => $detail->price ), 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span>Ongkos Kirim</span>
                <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span>Total</span>
                <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="d-flex my-3">
            <a href="{{ route('pesanan.index') }}" class="btn btn-cancel ">kembali</a>
        </div>
    </div>
@endsection
