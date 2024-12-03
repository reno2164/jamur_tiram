@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h3 class="text-center mb-4">Hasil Peringkat TPK</h3>

    <!-- Tabel Hasil Peringkat -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <strong>Hasil Peringkat</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Peringkat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item['user'] }}</td>
                        <td>{{ $item['peringkat'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
