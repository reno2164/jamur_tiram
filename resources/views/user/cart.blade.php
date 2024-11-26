@extends('layouts.user')

@section('content')
    <style>
        /* Styling for the cart items */
        .cart-item {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-details h5 {
            font-size: 16px;
            font-weight: 600;
        }

        .product-details p {
            font-size: 14px;
            color: #6c757d;
        }

        .product-price,
        .total-price {
            font-size: 16px;
            font-weight: 600;
        }

        .quantity-input input {
            width: 80px;
            padding: 5px;
        }

        .actions {
            display: flex;
            align-items: center;
        }

        .actions .btn {
            font-size: 12px;
            padding: 5px 10px;
        }

        /* Total price styling */
        .total-summary {
            font-size: 18px;
            font-weight: 600;
        }

        .checkout-button {
            margin-top: 20px;
        }

        /* Additional styling for buttons and total */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
    <div class="container">
            <form action="{{ route('checkout') }}" method="POST" id="cartForm">
                @csrf
                <div class="container">
                    <h1>Keranjang Belanja</h1>
                    @if ($carts->isEmpty())
                        <p>Keranjang Anda kosong. </p>
                        <a href="{{ route('shop') }}" class="btn btn-warning">Belanja Sekarang</a>

                    @else
                        <table class="table table-borderless align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col">Kuantitas</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalPrice = 0; @endphp
                                @foreach ($carts as $cart)
                                    <tr data-cart-id="{{ $cart->id }}">

                                        <!-- Product Info -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/products/' . $cart->product->image) }}"
                                                    alt="{{ $cart->product->title }}" class="product-image" width="80">
                                                <div class="ms-3">
                                                    <h5>{{ $cart->product->title }}</h5>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Price per unit -->
                                        <td>
                                            <span>Rp {{ number_format($cart->product->price, 0, ',', '.') }}</span>
                                        </td>

                                        <!-- Quantity Input -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <!-- Input jumlah -->
                                                <input type="text" min="0.1" max="{{ $cart->product->stok }}"
                                                    name="quantity[{{ $cart->id }}]" value="{{ $cart->quantity }}"
                                                    class="form-control quantity-input" style="width: 80px;"
                                                    data-cart-id="{{ $cart->id }}"
                                                    data-stock="{{ $cart->product->stok }}">

                                                <!-- Dropdown untuk memilih satuan -->
                                                <select name="unit[{{ $cart->id }}]"
                                                    class="form-select ms-2 unit-select" style="width: 80px;"
                                                    data-cart-id="{{ $cart->id }}">
                                                    <option value="kg" selected>kg</option>
                                                    <option value="gram">gram</option>
                                                </select>

                                            </div>
                                        </td>

                                        <!-- Total Price -->
                                        <td>
                                            @php
                                                $totalPriceItem = $cart->product->price * $cart->quantity;
                                                $totalPrice += $totalPriceItem;
                                            @endphp
                                            <span class="item-total-price">Rp
                                                {{ number_format($totalPriceItem, 0, ',', '.') }}</span>
                                        </td>

                                        <!-- Remove Button -->
                                        <td>
                                            <a href="{{ route('removeFromCart', $cart->id) }}"
                                                class="btn btn-danger btn-sm">x</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('checkout') }}" id="checkoutButton" class="btn btn-success">Checkout</a>
                        </div>
                    @endif
                </div>
            </form>
    </div>

    <script>
        document.getElementById('checkoutButton').addEventListener('click', function () {
        let invalidQuantity = false;

        document.querySelectorAll('.quantity-input').forEach(input => {
            const stock = parseFloat(input.getAttribute('data-stock'));
            const unit = document.querySelector(`.unit-select[data-cart-id="${input.getAttribute('data-cart-id')}"]`).value;
            const quantity = parseFloat(input.value.replace(',', '.')) || 0;

            // Validasi stok berdasarkan unit
            let maxStock = stock; // Default untuk kg
            if (unit === 'gram') {
                maxStock = stock * 1000; // Konversi ke gram
            }

            if (quantity > maxStock || quantity <= 0) {
                invalidQuantity = true;
            }
        });

        if (invalidQuantity) {
            alert('Ada produk yang melebihi stok yang tersedia atau jumlah tidak valid. Mohon periksa kembali.');
        } else {
            document.getElementById('cartForm').submit(); // Lanjutkan ke checkout
        }
    });
        // Fungsi untuk menangani perubahan unit
        function handleUnitChange(event) {
            const cartId = event.target.getAttribute('data-cart-id');
            const quantityInput = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
            const stock = parseFloat(quantityInput.getAttribute('data-stock'));

            // Tentukan stok maksimum berdasarkan unit
            let maxStock = stock; // Default untuk kg
            if (event.target.value === 'gram') {
                maxStock = stock * 1000; // Konversi ke gram
            }

            // Perbarui atribut max pada input quantity
            quantityInput.setAttribute('max', maxStock);

            // Validasi ulang nilai input
            if (parseFloat(quantityInput.value) > maxStock) {
                quantityInput.value = maxStock;
                alert('Jumlah melebihi stok yang tersedia!');
            }

            // Panggil updateTotalPrice
            updateTotalPrice();
        }

        // Fungsi untuk menangani perubahan kuantitas
        function handleQuantityChange(event) {
            const input = event.target;
            const cartId = input.getAttribute('data-cart-id');
            const unit = document.querySelector(`.unit-select[data-cart-id="${cartId}"]`).value;
            const stock = parseFloat(input.getAttribute('data-stock'));

            let maxStock = stock;
            if (unit === 'gram') {
                maxStock = stock * 1000;
            }

            let quantity = parseFloat(input.value.replace(',', '.')) || 0;

            // Validasi kuantitas
            if (quantity > maxStock) {
                input.value = maxStock;
                alert('Jumlah melebihi stok yang tersedia!');
            } else if (quantity <= 0) {
                input.value = '';
            }

            // Kirim data kuantitas baru ke server
            fetch("{{ route('cart.updateQuantity') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cart_id: cartId,
                        quantity: unit === 'gram' ? quantity / 1000 : quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update total harga item di UI
                        document.querySelector(`tr[data-cart-id="${cartId}"] .item-total-price`).textContent = 'Rp ' +
                            data.total_price_item.toLocaleString('id-ID');

                        // Update total harga keseluruhan
                        updateTotalPrice();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Tambahkan event listener ke elemen terkait
        document.querySelectorAll('.cart-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalPrice);
        });

        document.querySelectorAll('.unit-select').forEach(select => {
            select.addEventListener('change', handleUnitChange);
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', handleQuantityChange);
        });

        // Panggil updateTotalPrice saat halaman dimuat
        updateTotalPrice();
    </script>

@endsection
