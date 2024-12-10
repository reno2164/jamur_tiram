@extends('layouts.user')

@section('content')
    <style>
        .swal2-popup {
            background: rgba(255, 255, 255, 0.6) !important;
            /* Transparansi lebih halus */
            backdrop-filter: blur(10px);
            /* Blur lebih intens */
            border-radius: 20px;
            /* Sudut melengkung */
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2) !important;
            /* Bayangan lembut */
            border: 1px solid rgba(255, 255, 255, 0.2);
            /* Border halus */
        }

        /* Title and Content Styling */
        .swal2-title {
            font-size: 1.8rem !important;
            /* Ukuran font judul */
            font-weight: bold !important;
            /* Tebal */
            color: #333 !important;
            /* Warna teks */
            text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
            /* Bayangan halus */
        }

        .swal2-content {
            font-size: 1.2rem !important;
            /* Ukuran font isi */
            color: #444 !important;
            /* Warna teks isi */
        }

        /* Button Styling */
        .swal2-confirm {
            background: linear-gradient(135deg, #4caf50, #81c784) !important;
            /* Gradasi hijau */
            color: white !important;
            /* Warna teks putih */
            font-weight: bold !important;
            border: none !important;
            /* Hilangkan border */
            border-radius: 10px !important;
            /* Tombol melengkung */
            padding: 10px 20px !important;
            /* Padding tombol */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            /* Bayangan tombol */
        }

        /* Container Styling */
        .order-container {
            margin: 20px auto;
            max-width: 1200px;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 1px solid #e0e0e0;
        }

        /* Tab Navigation Styling */
        .order-tabs .nav-link {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            background-color: #8d8072;
            margin-right: 5px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .order-tabs .nav-link.active {
            color: #ffffff;
            background-color: #49443a;
            border: 1px solid #8d8072;
        }

        /* Order List Styling */
        .order-list {
            margin-top: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #f7f4f0;
            border-radius: 8px;
            border: 1px solid #dcd7c7;
            margin-bottom: 15px;
            transition: transform 0.2s ease, box-shadow 0.3s;
        }

        .order-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .order-item .details {
            max-width: 70%;
        }

        .order-item .details .title {
            font-size: 18px;
            font-weight: bold;
            color: #49443a;
        }

        .order-item .details .status {
            font-size: 14px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .status-unpaid {
            background: #f5c6cb;
            color: #721c24;
        }

        .status-processing {
            background: #ffeeba;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .order-item .details .price {
            font-size: 14px;
            font-weight: bold;
            color: #6c757d;
        }

        /* Button Styling */
        .btn {
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .btn-bayar {
            background-color: #49443a;
            color: white;
        }

        .btn-bayar:hover {
            background-color: #3a372f;
        }

        .btn-detail {
            background-color: #8d8072;
            color: white;
        }

        .btn-detail:hover {
            background-color: #776a61;
        }

        .btn-cancel {
            background-color: #cc3d3d;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #a32a2a;
        }

        /* Empty State Styling */
        .empty-state {
            text-align: center;
            color: #6c757d;
            margin-top: 20px;
        }

        .empty-state img {
            max-width: 200px;
            margin-bottom: 10px;
        }
    </style>

    <div class="order-container">
        <ul class="nav nav-tabs order-tabs" id="orderTabs" role="tablist">
            @php
                $statuses = [
                    'Semua' => null,
                    'Belum Dibayar' => 'unpaid',
                    'Sedang Dikemas' => 'processing',
                    'Selesai' => 'completed',
                ];
            @endphp
            @foreach ($statuses as $label => $id)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $id ?? 'all' }}-tab"
                        data-bs-toggle="tab" data-bs-target="#{{ $id ?? 'all' }}" type="button" role="tab"
                        aria-controls="{{ $id ?? 'all' }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $label }}
                        @if ($id && $label !== 'Selesai' && $orders->where('status', $label)->count())
                            ({{ $orders->where('status', $label)->count() }})
                        @endif
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content order-list" id="orderTabsContent">
            @foreach ($statuses as $label => $id)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $id ?? 'all' }}" role="tabpanel"
                    aria-labelledby="{{ $id ?? 'all' }}-tab">
                    @php
                        $filteredOrders = $id ? $orders->where('status', $label) : $orders;
                    @endphp

                    @forelse ($filteredOrders as $order)
                        <div class="order-item">
                            <div class="details">
                                <p class="title">Kode Pesanan: {{ $order->transaction_code }}</p>
                                <p>Status:
                                    <span
                                        class="status {{ $order->status === 'Belum Dibayar' ? 'status-unpaid' : ($order->status === 'Sedang Dikemas' ? 'status-processing' : 'status-completed') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                                <p>Total Harga: <span
                                        class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                            </div>
                            <div class="action d-flex gap-2">
                                @if ($order->status === 'Belum Dibayar')
                                    <a href="{{ route('checkout.pay', $order->transaction_code) }}"
                                        class="btn btn-bayar">Bayar</a>
                                    <button type="button" class="btn btn-cancel" data-bs-toggle="modal"
                                        data-bs-target="#delete{{ $order->id }}">
                                        Batalkan
                                    </button>
                                @elseif ($order->status === 'Sedang Dikemas')
                                    <a href="{{ route('orders.detail', $order->transaction_code) }}"
                                        class="btn btn-detail">Detail</a>
                                    <button type="button" class="btn btn-cancel" data-bs-toggle="modal"
                                        data-bs-target="#delete{{ $order->id }}">
                                        Batalkan
                                    </button>
                                @else
                                    <a href="{{ route('orders.detail', $order->transaction_code) }}"
                                        class="btn btn-detail">Detail</a>
                                @endif
                            </div>
                        </div>
                        <div class="modal fade" id="delete{{ $order->id }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $order->id }}">Konfirmasi
                                            Pembatalan Pesanan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin membatalkan pesanan ini?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('transactions.cancel', $order->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-cancel">Ya</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tidak</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <img src="{{ asset('image/not-pesanan.png') }}" alt="Empty Orders" style="width: 90px">
                            <p>Belum Ada Pesanan</p>
                        </div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert message handling
        @if (session('success'))
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "{{ session('success') }}",
                showConfirmButton: true,
                confirmButtonText: "OK",
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    content: 'swal2-content',
                    confirmButton: 'swal2-confirm'
                },
                timer: 2000
            });
        @elseif (session('error'))
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "{{ session('error') }}",
                showConfirmButton: true,
                confirmButtonText: "Coba Lagi",
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    content: 'swal2-content',
                    confirmButton: 'swal2-confirm'
                },
                timer: 9000
            });
        @endif
    </script>
@endsection
