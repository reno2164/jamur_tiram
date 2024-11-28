@extends('layouts.user')

@section('content')
    <div class="container">
        <h1 class="my-4">Riwayat Transaksi</h1>

        @if ($transactions->isEmpty())
            <div class="alert alert-info">
                Anda belum memiliki transaksi.
            </div>
        @else
            <div class="card-body card rounded-full table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Transaksi</th>
                            <th>Total Harga</th>
                            <th>Metode Pembayaran</th>
                            <th>Nama Penerima</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaction->transaction_code }}</td>
                                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td>{{ $transaction->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'COD' }}</td>
                                <td>
                                    <strong>{{ $transaction->address->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($transaction->status == 'Berhasil' || $transaction->status == 'Pesanan Disiapkan') bg-success 
                                        @elseif($transaction->status == 'Gagal') bg-danger 
                                        @elseif($transaction->status == 'Menunggu Pembayaran') bg-warning 
                                        @else bg-secondary 
                                        @endif">
                                        {{ $transaction->status }}
                                    </span>
                                </td>                                
                                <td>

                                    @if ($transaction->status === 'Menunggu Pembayaran')
                                        <a href="{{ route('checkout.pay', ['transaction_code' => $transaction->transaction_code]) }}"
                                            class="btn btn-warning btn-sm">
                                            Bayar Sekarang
                                        </a>
                                    @else
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $transaction->id }}">
                                            Lihat Detail
                                        </button>

                                        <!-- Modal Detail -->
                                        <div class="modal fade" id="detailModal{{ $transaction->id }}" tabindex="-1"
                                            aria-labelledby="detailModalLabel{{ $transaction->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="detailModalLabel{{ $transaction->id }}">Detail Transaksi:
                                                            {{ $transaction->transaction_code }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Produk</th>
                                                                    <th>Kuantitas</th>
                                                                    <th>Harga</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($transaction->transactionDetails as $detail)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $detail->product->title }}</td>
                                                                        <td>
                                                                            @if ($detail->quantity >= 1000)
                                                                                {{ $detail->quantity / 1000 }} kg
                                                                            @else
                                                                                {{ $detail->quantity }} gram
                                                                            @endif
                                                                        </td>
                                                                        <td>Rp
                                                                            {{ number_format($detail->price, 0, ',', '.') }}
                                                                        </td>
                                                                        <td>Rp
                                                                            {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="4" class="text-end">Total</th>
                                                                    <th>Rp
                                                                        {{ number_format($transaction->total_price, 0, ',', '.') }}
                                                                    </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- End Modal -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
