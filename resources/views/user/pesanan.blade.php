@extends('layouts.user')

@section('content')
    <style>
        .order-container {
            margin: 30px auto;
            max-width: 1200px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .order-tabs .nav-link {
            font-size: 16px;
            font-weight: 500;
            color: #555;
            transition: color 0.3s;
        }

        .order-tabs .nav-link.active {
            color: #ffffff;
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .order-list {
            margin-top: 20px;
        }

        .order-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item .details {
            font-size: 14px;
        }

        .order-item .details .title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .order-item .details .status {
            font-size: 14px;
            font-weight: 500;
            color: #4CAF50;
        }

        .order-item .details .price {
            font-weight: 600;
            color: #333;
        }

        .order-item .action {
            text-align: right;
        }

        .btn-detail {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .btn-bayar {
            background-color: #386dff;
            color: #ffffff;
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn-detail:hover {
            background-color: #45a049;
            color: #ffffff;
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
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="order-container">
        <ul class="nav nav-tabs order-tabs" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button"
                    role="tab" aria-controls="all" aria-selected="true">Semua</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button"
                    role="tab" aria-controls="unpaid" aria-selected="false">Belum Dibayar</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="processing-tab" data-bs-toggle="tab" data-bs-target="#processing"
                    type="button" role="tab" aria-controls="processing" aria-selected="false">Sedang Dikemas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button"
                    role="tab" aria-controls="completed" aria-selected="false">Selesai</button>
            </li>
        </ul>

        <div class="tab-content order-list" id="orderTabsContent">
            <!-- Semua Pesanan -->
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                @forelse ($orders as $order)
                    <div class="order-item">
                        <div class="details">
                            <p class="title">Kode Pesanan: {{ $order->transaction_code }}</p>
                            <p>Status: <span class="status">{{ ucfirst($order->status) }}</span></p>
                            <p>Total Harga: <span
                                    class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="action">
                            @if ($order->status === 'Belum Dibayar')
                                <a href="{{ route('checkout.pay', ['transaction_code' => $order->transaction_code]) }}"
                                    class="btn-bayar">Bayar</a>
                                <a href="{{ route('transactions.cancel', ['transaction_id' => $order->id]) }}"
                                    class="btn-detail btn-cancel">Batalkan Pesanan</a>
                            @elseif ($order->status === 'Sedang Dikemas')
                            <a href="{{ route('orders.detail', $order->transaction_code) }}" class="btn-detail">Lihat Detail</a>
                            <a href="{{ route('transactions.cancel', ['transaction_id' => $order->id]) }}"
                                class="btn-detail btn-cancel">Batalkan Pesanan</a>
                            @else
                            <a href="{{ route('orders.detail', $order->transaction_code) }}" class="btn-detail">Lihat Detail</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p>Tidak ada pesanan.</p>
                @endforelse
            </div>

            <!-- Pesanan Belum Dibayar -->
            <div class="tab-pane fade" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                @forelse ($orders->where('status', 'Belum Dibayar') as $order)
                    <div class="order-item">
                        <div class="details">
                            <p class="title">Kode Pesanan: {{ $order->transaction_code }}</p>
                            <p>Status: <span class="status">{{ ucfirst($order->status) }}</span></p>
                            <p>Total Harga: <span
                                    class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="action">
                            <a href="{{ route('checkout.pay', ['transaction_code' => $order->transaction_code]) }}"
                                class="btn-bayar">Bayar</a>
                        </div>
                    </div>
                @empty
                    <p>Tidak ada pesanan yang belum dibayar.</p>
                @endforelse
            </div>

            <!-- Pesanan Sedang Dikemas -->
            <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                @forelse ($orders->where('status', 'Sedang Dikemas') as $order)
                    <div class="order-item">
                        <div class="details">
                            <p class="title">Kode Pesanan: {{ $order->transaction_code }}</p>
                            <p>Status: <span class="status">{{ ucfirst($order->status) }}</span></p>
                            <p>Total Harga: <span
                                    class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="action d-flex gap-2">
                            @if ($order->status === 'Sedang Dikemas')
                            <a href="{{ route('orders.detail', $order->transaction_code) }}" class="btn-detail">Lihat Detail</a>
                                <form action="{{ route('transactions.cancel', ['transaction_id' => $order->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-detail btn-cancel">Batalkan Pesanan</button>
                                </form>
                            @else
                            <a href="{{ route('orders.detail', $order->transaction_code) }}" class="btn-detail">Lihat Detail</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p>Tidak ada pesanan yang sedang dikemas.</p>
                @endforelse
            </div>

            <!-- Pesanan Selesai -->
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                @forelse ($orders->where('status', 'Selesai') as $order)
                    <div class="order-item">
                        <div class="details">
                            <p class="title">Kode Pesanan: {{ $order->transaction_code }}</p>
                            <p>Status: <span class="status">{{ ucfirst($order->status) }}</span></p>
                            <p>Total Harga: <span
                                    class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="action">
                            <a href="{{ route('orders.detail', $order->transaction_code) }}" class="btn-detail">Lihat Detail</a>
                        </div>
                    </div>
                @empty
                    <p>Tidak ada pesanan yang selesai.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi tab dengan Bootstrap
        var triggerTabList = [].slice.call(document.querySelectorAll('#orderTabs button'))
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function(event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    </script>
@endsection
