@extends('layouts.user')

@section('content')
    <div class="container">
        <h1>Tambah Alamat Baru</h1>
        <form action="{{ route('addresses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Penerima</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="street" class="form-label">Alamat Lengkap</label>
                <input type="text" name="street" id="street" class="form-control" required>
            </div>
            <button type="button" class="btn btn-danger">Batal</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
@endsection
