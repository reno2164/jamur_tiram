@extends('layouts.main')

@section('title', 'Hasil Perhitungan SAW')

@section('content')
    <h1>Hasil Perhitungan dengan Metode SAW</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kode</th>
                <th>Berat (gram)</th>
                <th>Harga</th>
                <th>Pembelian</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_kriteria as $data)
                <tr>
                    <td>{{ $data['No'] }}</td>
                    <td>{{ $data['Nama'] }}</td>
                    <td>{{ $data['Kode'] }}</td>
                    <td>{{ $data['Berat'] }}</td>
                    <td>{{ $data['Harga'] }}</td>
                    <td>{{ $data['Pembelian'] }}</td>
                    <td>{{ number_format($data['Skor'], 4) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Alternatif Terbaik</h2>
    <table class="table table-striped">
        <tr>
            <th>Nama</th>
            <td>{{ $alternatif_terbaik['Nama'] }}</td>
        </tr>
        <tr>
            <th>Kode</th>
            <td>{{ $alternatif_terbaik['Kode'] }}</td>
        </tr>
        <tr>
            <th>Skor</th>
            <td>{{ number_format($alternatif_terbaik['Skor'], 4) }}</td>
        </tr>
    </table>
@endsection
