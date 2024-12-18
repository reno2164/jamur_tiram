@extends('admin.layout.index')

@section('content')
<div class="container mt-5">
    <h3>Data Penjualan dengan TPK</h3>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Transaksi</th>
                <th>Pelanggan</th>
                <th>Harga</th>
                <th>Berat</th>
                <th>Pembelian Bulan</th>
                <th>TPK</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tpk_data as $dataPenjualan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dataPenjualan->transaction_code }}</td>
                    <td>{{ $dataPenjualan->user->username }}</td>
                    <td>Rp {{ number_format($dataPenjualan->harga, 0, ',', '.') }}</td>
                    <td>{{ $dataPenjualan->berat }} kg</td>
                    <td>{{ $dataPenjualan->pembelian_bulan }} pcs</td>
                    <td>{{ number_format($dataPenjualan->tpk, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
