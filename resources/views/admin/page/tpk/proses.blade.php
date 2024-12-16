@extends('admin.layout.index')

@section('content')
    <div class="container">
        <h1 class="mb-4">Hasil SPK - Metode SAW</h1>

        <h3>Hasil Normalisasi</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Berat</th>
                    <th>Harga</th>
                    <th>Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($normalisasi as $row)
                    <tr>
                        <td>K{{ $row['user_id'] }}</td>
                        <td>{{ $row['username'] }}</td>
                        <td>{{ number_format($row['quantity'], 3) }}</td>
                        <td>{{ number_format($row['price'], 3) }}</td>
                        <td>{{ number_format($row['transactions'], 3) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="mt-5">Hasil Akhir</h3>
        <form action="{{ route('spk.saw.store') }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Skor Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        <tr>
                            <td>K{{ $result['user_id'] }}</td>
                            <td>{{ $result['username'] }}</td>
                            <td>{{ number_format($result['score'], 3) }}</td>
                        </tr>
                        <!-- Hidden input untuk mengirim data ke controller -->
                        <input type="hidden" name="results[{{ $loop->index }}][user_id]" value="{{ $result['user_id'] }}">
                        <input type="hidden" name="results[{{ $loop->index }}][username]"
                            value="{{ $result['username'] }}">
                        <input type="hidden" name="results[{{ $loop->index }}][score]" value="{{ $result['score'] }}">
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary mt-3">Simpan Hasil</button>
        </form>
    </div>
@endsection
