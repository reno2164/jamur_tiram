@extends('admin.layout.index')

@section('content')
    <div class="container">
        <h1 class="my-4">Daftar Transaksi</h1>
        <div class="card-body card rounded-full">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Nama User</th>
                            <th>Total Harga</th>
                            <th>Metode Pembayaran</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaction->transaction_code }}</td>
                                <td>{{ $transaction->user->username }}</td>
                                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td>{{ $transaction->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'COD' }}</td>
                                <td>
                                    <strong>{{ $transaction->address->name }}</strong> <br>
                                    {{ $transaction->address->address }} <br>
                                    <small>No. HP: {{ $transaction->address->phone_number }}</small>
                                </td>
                                <td>
                                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select" onchange="this.form.submit()">
                                            <option value="Pesanan Disiapkan" {{ $transaction->status == 'Pesanan Disiapkan' ? 'selected' : '' }}>Pesanan Disiapkan</option>
                                            <option value="Menunggu Pembayaran" {{ $transaction->status == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                            <option value="Berhasil" {{ $transaction->status == 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                                            <option value="Gagal" {{ $transaction->status == 'Gagal' ? 'selected' : '' }}>Gagal</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
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
                                                    <h5 class="modal-title" id="detailModalLabel{{ $transaction->id }}">
                                                        Detail Transaksi: {{ $transaction->transaction_code }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
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
                                                                    <td>{{ $detail->quantity }}</td>
                                                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}
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
                                    <!-- End Modal -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
