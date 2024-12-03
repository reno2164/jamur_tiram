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

    <!-- Form Input Bobot -->
    <form action="{{ route('admin.saw') }}" method="POST" class="mt-4">
        @csrf
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong>Input Bobot Kriteria</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Kriteria</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Harga</td>
                            <td>
                                <input type="number" name="bobot_harga" class="form-control" step="0.0001" placeholder="Masukkan bobot (contoh: 0.0001)" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Berat</td>
                            <td>
                                <input type="number" name="bobot_berat" class="form-control" step="0.0001" placeholder="Masukkan bobot (contoh: 0.0001)" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Pembelian Bulan</td>
                            <td>
                                <input type="number" name="bobot_pembelian_bulan" class="form-control" step="0.0001" placeholder="Masukkan bobot (contoh: 0.0001" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-success mt-3">Proses</button>
            </div>
        </div>
    </form>

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
