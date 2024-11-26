@extends('layouts.user')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">Ubah Alamat</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('addresses.update', $address->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $address->name) }}" required>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                   value="{{ old('phone_number', $address->phone_number) }}" required>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $address->address) }}</textarea>
                        </div>

                        <!-- Checkbox: Jadikan Alamat Utama -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_default" name="is_default" 
                                   {{ $address->is_default ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">
                                Jadikan Alamat Utama
                            </label>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('checkout') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
