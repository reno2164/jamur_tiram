@extends('layouts.user')

@section('content')
    <div class="container">
        <h1>Checkout</h1>

        <form action="{{ route('checkout.proses') }}" method="POST">
            @csrf
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">
                                <i class="bi bi-geo-alt-fill"></i> Alamat Pengiriman
                            </h5>
                            @if ($selectedAddress)
                                <p class="mb-1">
                                    <strong>{{ $selectedAddress->name }}</strong> ({{ $selectedAddress->phone_number }})
                                </p>
                                <p>{{ $selectedAddress->address }}</p>
                            @else
                                <p class="text-danger">Alamat pengiriman belum ada.</p>
                            @endif
                        </div>
                        <div>
                            @if ($selectedAddress)
                                <a href="{{ route('addresses.edit', $selectedAddress->id) }}"
                                    class="btn btn-link text-primary p-0">
                                    Ubah
                                </a>
                            @else
                                <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                                    Tambah Alamat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan -->
            <h5>Pesanan</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga/Kg</th>
                        <th>sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            <td>
                                <img class="mb-2" style="width: 50px"
                                    src="{{ asset('/storage/products/' . $cart->product->image) }}" alt="Gambar Produk">
                            </td>
                            <td>{{ $cart->product->title }}</td>
                            <td>{{ $cart->quantity }} Kg</td>
                            <td>{{ number_format($cart->product->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($cart->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Harga :</th>
                        <th>Rp {{ number_format($totalPrice, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            <!-- Metode Pembayaran -->
            <div class="mb-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer"
                            value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                        <label class="form-check-label" for="bank_transfer">
                            Transfer
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod"
                            {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                        <label class="form-check-label" for="cod">
                            Bayar di Tempat
                        </label>
                    </div>
                </div>
                @error('payment_method')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="btn btn-success">Checkout</button>

        </form>
    </div>
@endsection
