@extends('layouts.admin')

@section('title', 'Hasil Perhitungan SAW')

@section('content')
    <h2 class="text-center mb-4">Hasil Perhitungan SAW</h2>
    
    <!-- Tabel menggunakan kelas Bootstrap untuk tampilan yang responsif dan rapi -->
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kode</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->Nama }}</td>
                    <td>{{ $row->Kode }}</td>
                    <td>{{ $row->Skor }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Alternatif Terbaik -->
    <div class="alert alert-success mt-4">
        <h4>Alternatif Terbaik Berdasarkan Metode SAW:</h4>
        <p><strong>Nama:</strong> {{ $alternatif_terbaik->Nama }}</p>
        <p><strong>Kode:</strong> {{ $alternatif_terbaik->Kode }}</p>
        <p><strong>Skor:</strong> {{ $alternatif_terbaik->Skor }}</p>
    </div>
@endsection
