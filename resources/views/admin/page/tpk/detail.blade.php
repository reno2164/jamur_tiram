@extends('admin.layout.index')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Hasil TPK</h1>
    <h3>Waktu: {{ $datetime }}</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilDetail as $detail)
                <tr>
                    <td>K{{ $detail->user_id }}</td>
                    <td>{{ $detail->username }}</td>
                    <td>{{ number_format($detail->score, 3) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('hasil.tpk.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
