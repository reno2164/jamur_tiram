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
        <h1>Keranjang Belanja</h1>
        @if ($carts->isEmpty())
            <p>Keranjang Anda kosong.</p>
        @else
            <form action="{{ route('checkout') }}" method="POST" id="cartForm">
                @csrf
                <div class="container">
                    <h1>Keranjang Belanja</h1>
                    @if ($carts->isEmpty())
                        <p>Keranjang Anda kosong.</p>
                    @else
                        <table class="table table-borderless align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Pilih</th>
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
                                        <!-- Checkbox -->
                                        <td>
                                            <input type="checkbox" name="cart_items[]" value="{{ $cart->id }}"
                                                class="cart-checkbox" data-price="{{ $cart->product->price }}"
                                                data-quantity="{{ $cart->quantity }}">
                                        </td>

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
                                                <input type="text" min="0,1" max="{{ $cart->product->stok }}"
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
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong></strong></td>
                                    <td colspan="2" class="text-end">
                                        <strong id="total-price">Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Checkout</button>
                            <a href="{{ route('checkout') }}" class="btn btn-success">Checkout</a>
                        </div>
                    @endif
                </div>
            </form>
        @endif
    </div>

    <script>
        function updateTotalPrice() {
            let totalPrice = 0;
            let selectedItems = document.querySelectorAll('.cart-checkbox:checked');

            selectedItems.forEach(function(checkbox) {
                let productPrice = parseFloat(checkbox.getAttribute('data-price'));
                let cartId = checkbox.value;

                // Ambil input quantity dan unit
                let quantityInput = document.querySelector(`input[name="quantity[${cartId}]"]`);
                let unitSelect = document.querySelector(`select[name="unit[${cartId}]"]`);

                // Ambil nilai dari input quantity, ganti koma dengan titik untuk parsing
                let quantity = parseFloat(quantityInput.value.replace(',', '.'));
                let unit = unitSelect.value;

                // Validasi input: jika tidak valid, set ke 0
                if (isNaN(quantity) || quantity <= 0) {
                    quantity = 0;
                }

                // Jika satuan adalah gram, ubah jumlah menjadi kilogram
                if (unit === "gram") {
                    quantity = quantity / 1000;
                }

                // Hitung total harga untuk item ini
                let totalPriceItem = productPrice * quantity;

                // Update total harga per item di elemen terkait
                let itemRow = checkbox.closest('tr');
                let itemTotalPriceElement = itemRow.querySelector('.item-total-price');
                if (itemTotalPriceElement) {
                    itemTotalPriceElement.textContent = 'Rp ' + totalPriceItem.toLocaleString('id-ID', {
                        minimumFractionDigits: 2
                    });
                }

                // Tambahkan ke total keseluruhan
                totalPrice += totalPriceItem;
            });

            // Update total harga keseluruhan
            document.getElementById('total-price').textContent = 'Total: Rp ' + totalPrice.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            });
        }

        // Event listener untuk checkbox, input quantity, dan dropdown unit
        document.querySelectorAll('.cart-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', updateTotalPrice);
        });
        document.querySelectorAll('.quantity-input').forEach(function(input) {
            input.addEventListener('input', function() {
                // Pastikan input quantity valid dan panggil updateTotalPrice
                this.value = this.value.replace(',', '.'); // Ganti koma dengan titik
                updateTotalPrice();
            });
        });
        document.querySelectorAll('.unit-select').forEach(function(select) {
            select.addEventListener('change', updateTotalPrice);
        });

        // Validasi input angka
        document.querySelectorAll('.quantity-input').forEach(function(input) {
            input.addEventListener('input', function() {
                let value = this.value.replace(',', '.'); // Ganti koma dengan titik
                let stock = parseFloat(this.getAttribute('data-stock')); // Ambil stok dari atribut data
                let quantity = parseFloat(value);

                // Jika input tidak valid (bukan angka), kosongkan
                if (isNaN(quantity)) {
                    this.value = '';
                    return;
                }

                // Pastikan angka hanya positif, tidak 0, dan tidak melebihi stok
                if (quantity <= 0) {
                    this.value = '';
                } else if (quantity > stock) {
                    this.value = stock; // Jika lebih dari stok, set ke stok maksimal
                } else {
                    this.value = value; // Biarkan angka tetap valid
                }
            });
        });


        // Panggil fungsi updateTotalPrice saat halaman pertama kali dimuat
        updateTotalPrice();
    </script>
@endsection
