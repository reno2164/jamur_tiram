@extends('admin.layout.index')

@section('content')
<div class="container mt-5">
    <h3 class="text-center mb-4">{{ $title }}</h3>

    <div class="card">
        <div class="card-header bg-warning text-white">
            <strong>Detail Data TPK</strong>
        </div>
        <div class="card-body table-container">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Berat</th>
                        <th>Pembelian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tpk_data as $data)
                        <tr>
                            <td>{{ $data->username }}</td>
                            <td>Rp {{ number_format($data->total_price, 0, ',', '.') }}</td>
                            <td>{{ $data->total_weight }} kg</td>
                            <td>{{ $data->pembelian_bulan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
