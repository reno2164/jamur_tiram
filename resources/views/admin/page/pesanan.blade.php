@extends('admin.layout.index')

@section('content')
    <div class="container mt-5">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button"
                    role="tab" aria-controls="all" aria-selected="true">
                    Semua
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button"
                    role="tab" aria-controls="unpaid" aria-selected="false">
                    Belum Dibayar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="packaging-tab" data-bs-toggle="tab" data-bs-target="#packaging" type="button"
                    role="tab" aria-controls="packaging" aria-selected="false">
                    Sedang Dikemas
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="orderTabsContent">
            <!-- Tab Semua -->
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>User</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Metode Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($allOrders->isEmpty())
                                <tr class="text-center">
                                    <td colspan="7">Belum ada pesanan</td>
                                </tr>
                            @else
                                @foreach ($allOrders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->transaction_code }}</td>
                                        <td>{{ $order->user->username }}</td>
                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td>
                                            @if ($order->status == 'Sedang Dikemas')
                                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">Selesai</button>
                                                </form>
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="btn btn-sm btn-primary">Detail</a>
                                            @else
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="btn btn-sm btn-primary">Detail</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Belum Dibayar -->
            <div class="tab-pane fade" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>User</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Metode Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($unpaidOrders->isEmpty())
                                <tr class="text-center">
                                    <td colspan="7">Belum ada pesanan</td>
                                </tr>
                            @else
                                @foreach ($unpaidOrders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->transaction_code }}</td>
                                        <td>{{ $order->user->username }}</td>
                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-primary">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Sedang Dikemas -->
            <div class="tab-pane fade" id="packaging" role="tabpanel" aria-labelledby="packaging-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>User</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Metode Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($packagingOrders->isEmpty())
                                <tr class="text-center">
                                    <td colspan="7">Belum ada pesanan</td>
                                </tr>
                            @else
                                @foreach ($packagingOrders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->transaction_code }}</td>
                                        <td>{{ $order->user->username }}</td>
                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-primary">Detail</a>
                                            <!-- Tombol Ubah Status -->
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#Selesai{{ $order->id }}">
                                                Selesai
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="Selesai{{ $order->id }}" aria-labelledby="Selesai{{ $order->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $order->id }}">Konfirmasi Selesai</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin mengubah status ini?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success">Selesai</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
