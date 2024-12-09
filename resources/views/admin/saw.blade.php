@extends('admin.layout.index')

@section('content')
<div class="container mt-5">
   

    <!-- Filter Tanggal -->
    <form action="{{ route('admin.saw') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
            </div>
        </div>
    </form>

   <!-- Tabel Data TPK -->
<div class="card mt-4">
    <div class="card-header bg-warning text-white">
        <strong>Data TPK (AHP & SAW)</strong>
    </div>
    <div class="card-body table-container">
        <table class="table table-bordered table-striped">
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

<!-- Tombol Bobot AHP -->
<form action="{{ route('admin.calculate_ahp') }}" method="POST" class="mt-3">
    @csrf
    <button type="submit" class="btn btn-secondary">Hitung Bobot AHP</button>
</form>

<!-- Tabel Hasil Bobot AHP -->
@if(!empty($bobot_ahp))
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <strong>Hasil Perhitungan Bobot AHP</strong>
    </div>
    <div class="card-body table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kriteria</th>
                    <th>Bobot</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Harga</td>
                    <td>{{ number_format($bobot_ahp['harga'], 4) }}</td>
                </tr>
                <tr>
                    <td>Berat</td>
                    <td>{{ number_format($bobot_ahp['berat'], 4) }}</td>
                </tr>
                <tr>
                    <td>Pembelian dalam Sebulan</td>
                    <td>{{ number_format($bobot_ahp['pembelian'], 4) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif


    <!-- Tabel Hasil Peringkat -->
    @if(!empty($sorted_data) && $sorted_data->isNotEmpty())
    <div class="card mt-4">
        <div class="card-header bg-success text-white">
            <strong>Hasil Peringkat SAW</strong>
        </div>
        <div class="card-body table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Peringkat</th>
                        <th>Nama</th>
                        <th>Total Perhitungan TPK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sorted_data as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->username }}</td>
                            <td>{{ number_format($data->tpk, 4) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
