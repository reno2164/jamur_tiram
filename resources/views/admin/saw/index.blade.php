@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Hasil Perhitungan SAW</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Total Harga</th>
                <th>Berat</th>
                <th>Pembelian Bulan</th>
                <th>TPK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tpk_data as $data)
            <tr>
                <td>{{ $data->user->name }}</td>
                <td>{{ $data->total_price }}</td>
                <td>{{ $data->berat ?? 'N/A' }}</td>
                <td>{{ $data->pembelian_bulan }}</td>
                <td>{{ number_format($data->tpk, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
