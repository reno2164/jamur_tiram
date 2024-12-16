@extends('admin.layout.index')

@section('content')
<div class="container">
    <h1 class="mb-4">Hasil TPK</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Juara</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilTpk as $key => $row)
                <tr>
                    <td>{{ $row->waktu }}</td>
                    <td>{{ $juara[$key]->username }} (Skor: {{ number_format($juara[$key]->score, 3) }})</td>
                    <td>
                        <a href="{{ route('hasil.tpk.detail', $row->waktu) }}" class="btn btn-primary">Lihat Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
