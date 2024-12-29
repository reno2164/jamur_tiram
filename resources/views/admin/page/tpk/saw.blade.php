@extends('admin.layout.index')

@section('content')
    <div class="container">
        <h1 class="mb-4">Sistem Pendukung Keputusan (SPK) - Metode SAW</h1>

        <!-- Filter Tanggal -->
        <form action="{{ route('spk.saw.index') }}" method="GET">
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="start_date">Tanggal Awal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <!-- Tampilkan Bobot -->
        @if ($bobot)
            <h3>Bobot Kriteria</h3>
            <ul>
                <li>Quantity: {{ $bobot->quantity }}</li>
                <li>Price: {{ $bobot->price }}</li>
                <li>Transactions: {{ $bobot->transactions }}</li>
            </ul>
            <a href="{{ route('admin.tpk') }}" class="btn btn-warning">Edit Bobot</a>
        @else
            <p class="text-danger">Bobot belum diatur. <a href="{{ route('admin.tpk') }}">Atur Bobot</a></p>
        @endif
        <!-- Tampilkan Data -->
        @if (!empty($data))
            <h3 class="mt-5">Data TPK</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Kode</th>
                        <th>berat</th>
                        <th>Harga</th>
                        <th>Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>K{{ $row['user_id'] }}</td>
                            <td>{{ $row['username'] }}</td>
                            <td>{{ $row['quantity'] }}</td>
                            <td>{{ $row['price'] }}</td>
                            <td>{{ $row['transactions'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('spk.saw.process') }}" method="POST">
                @csrf
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="btn btn-success mt-4">Proses SAW</button>
            </form>
        @else
            <p class="text-danger mt-3">Tidak ada data untuk ditampilkan.</p>
        @endif

    </div>
@endsection
