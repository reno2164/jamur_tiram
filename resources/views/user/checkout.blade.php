@extends('layouts.user')

@section('content')
    <div class="container">
        <h1>Checkout</h1>

        <form action="{{ url('/checkout') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="address" class="form-label">Alamat Pengiriman</label>
                <select name="address_id" class="form-select" required>
                    @foreach ($addresses as $address)
                        <option value="{{ $address->id }}" {{ old('address_id') == $address->id ? 'selected' : '' }}>
                            {{ $address->name }} - {{ $address->address }} ({{ $address->phone_number }})
                        </option>
                    @endforeach
                </select>
                @error('address_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <select name="payment_method" class="form-select" required>
                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                </select>
                @error('payment_method')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <h4>Detail Keranjang</h4>
            <ul>
                @php $totalPrice = 0; @endphp
                @foreach ($carts as $cart)
                    <li>{{ $cart->product->title }} - {{ $cart->quantity }} x Rp {{ number_format($cart->price, 0, ',', '.') }}</li>
                    @php $totalPrice += $cart->price * $cart->quantity; @endphp
                @endforeach
            </ul>

            <h4>Total Harga: Rp {{ number_format($totalPrice, 0, ',', '.') }}</h4>

            <button type="submit" class="btn btn-success">Bayar Sekarang</button>
        </form>
    </div>
@endsection
