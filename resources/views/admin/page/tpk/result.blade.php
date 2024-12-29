@extends('admin.layout.index')

@section('content')
<div class="container">
    <h1 class="mb-4">Hasil SPK - Metode AHP</h1>

    <!-- Tabel Matriks Perbandingan Kriteria -->
    <h3>Matriks Perbandingan Kriteria</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kriteria</th>
                @foreach ($kriteria as $k)
                    <th>{{ $k }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($matriks as $i => $row)
                <tr>
                    <th>{{ $kriteria[$i] }}</th>
                    @foreach ($row as $value)
                        <td>{{ number_format($value, 3) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tabel Matriks Normalisasi -->
    <h3 class="mt-5">Matriks Normalisasi</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kriteria</th>
                @foreach ($kriteria as $k)
                    <th>{{ $k }}</th>
                @endforeach
                <th>Bobot Kriteria</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matriksNormalisasi as $i => $row)
                <tr>
                    <th>{{ $kriteria[$i] }}</th>
                    @foreach ($row as $value)
                        <td>{{ number_format($value, 3) }}</td>
                    @endforeach
                    <td>{{ number_format($bobotKriteria[$i], 3) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Form Simpan Bobot -->
    <form action="{{ route('bobot.store') }}" method="POST">
        @csrf
        <input type="hidden" name="quantity" value="{{ $bobotKriteria[0] }}">
        <input type="hidden" name="price" value="{{ $bobotKriteria[1] }}">
        <input type="hidden" name="transactions" value="{{ $bobotKriteria[2] }}">

        <a href="{{ route('spk.saw.index') }}" class="btn btn-md btn-danger mx-3">Batal</a>
        <button type="submit" class="btn btn-success mt-3">Simpan Bobot</button>
    </form>
</div>
@endsection
