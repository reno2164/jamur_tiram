@extends('layouts.user')

@section('content')
    <style>
        /* Gaya untuk halaman produk */
        .product-detail {
            margin-top: 40px;
            margin-bottom: 50px;
        }

        .product-images {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .thumbnail-images {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .thumbnail-images img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            margin-right: 12px;
            border-radius: 6px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.3s ease, transform 0.3s ease;
        }

        .thumbnail-images img:hover {
            transform: scale(1.05);
        }

        .thumbnail-images img.active {
            border-color: #007bff;
        }

        .price {
            font-size: 28px;
            color: #ff5722;
            font-weight: 600;
            margin-right: 10px;
        }

        .original-price {
            text-decoration: line-through;
            color: #9e9e9e;
            font-size: 20px;
        }

        .discount-badge {
            color: #fff;
            background-color: #ff5e57;
            padding: 5px 12px;
            border-radius: 10px;
            font-size: 16px;
        }

        .stock-info {
            font-size: 16px;
            color: #757575;
            margin-top: 15px;
        }

        .quantity-input {
            display: inline-flex;
            align-items: center;
            margin-top: 15px;
        }

        .quantity-input input {
            width: 60px;
            height: 40px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            margin-right: 10px;
        }

        .quantity-input button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .quantity-input button:hover {
            background-color: #0056b3;
        }

        .btn-custom {
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 25px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-cart {
            background-color: #007bff;
            color: #fff;
            margin-right: 15px;
        }

        .btn-cart:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: #ffef11;
            color: #fff;
        }

        .btn-warning:hover {
            background-color: #fdd835;
            transform: translateY(-2px);
        }

        .btn-buy {
            background-color: #28a745;
            color: #fff;
        }

        .btn-buy:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        /* Media Queries untuk Responsif */
        @media (max-width: 767px) {
            .product-images {
                text-align: center;
                margin-bottom: 20px;
            }

            .main-image {
                height: auto;
                max-width: 90%;
            }

            .thumbnail-images {
                justify-content: center;
                margin-top: 10px;
            }

            .thumbnail-images img {
                width: 50px;
                height: 50px;
                margin-right: 10px;
            }

            .price {
                font-size: 24px;
            }

            .btn-custom {
                width: 100%;
                margin-bottom: 15px;
            }
        }
    </style>
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
    <div class="container product-detail">
        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6 product-images">
                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->title }}" class="main-image">
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <form action="{{ route('addTocart') }}" method="POST">
                    <h1 class="mb-3" style="font-size: 28px; font-weight: 600;">{{ $product->title }}</h1>
                    <p>{{ $product->description }}</p>
                    <div class="d-flex align-items-center mb-3">
                        <span class="price">Rp
                            {{ number_format($product->price - ($product->price * $product->discount) / 100) }}</span>
                        @if ($product->discount > 0)
                            <span class="original-price ms-3">Rp {{ number_format($product->price) }}</span>
                            <span class="discount-badge ms-3">-{{ $product->discount }}%</span>
                        @endif
                    </div>
                    <div class="stock-info">Stok tersedia: {{ $product->stok }} / Terjual: {{ $product->qty_out }}</div>

                    <!-- Quantity Input -->
                    <div class="quantity-input d-flex align-items-center">
                        <input type="text" min="0.1" max="{{ $product->stok }}" name="quantity" value="1"
                            class="form-control quantity-input" style="width: 80px;" data-stock="{{ $product->stok }}">

                        <!-- Dropdown untuk memilih satuan -->
                        <select name="unit" class="form-select ms-2 unit-select" style="width: 80px;">
                            <option value="kg" selected>kg</option>
                            <option value="gram">gram</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4">
                        @if (!Auth::check())
                            <a href="{{ route('login') }}" class="btn btn-cart btn-custom">
                                <i class="fas fa-shopping-cart"></i> Masukkan Keranjang
                            </a>
                        @else
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            @if ($isInCart)
                                <!-- Jika produk sudah ada di keranjang, tampilkan tombol untuk menuju ke keranjang -->
                                <a href="{{ route('cart') }}" class="btn btn-warning btn-custom">
                                    <i class="fas fa-shopping-cart"></i> Menuju Ke Keranjang
                                </a>
                            @else
                                <!-- Jika produk belum ada di keranjang, tampilkan tombol "Masukkan Keranjang" -->
                                <button type="submit" class="btn btn-cart btn-custom">
                                    <i class="fas fa-shopping-cart"></i> Masukkan Keranjang
                                </button>
                            @endif
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.querySelector('.quantity-input input');
            const unitSelect = document.querySelector('.unit-select');
            const maxStock = parseFloat(quantityInput.getAttribute('data-stock'));

            // Update max input value based on unit selection
            unitSelect.addEventListener('change', function() {
                if (unitSelect.value === 'gram') {
                    quantityInput.max = maxStock * 1000; // Jika gram, max = stok * 1000
                } else {
                    quantityInput.max = maxStock; // Jika kg, max = stok asli
                }
            });

            // Validasi input agar hanya angka dan koma yang diperbolehkan
            quantityInput.addEventListener('input', function(e) {
                // Hapus semua karakter yang bukan angka atau koma
                quantityInput.value = quantityInput.value.replace(/[^0-9,]/g, '');

                // Ganti koma menjadi titik (untuk parsing angka desimal)
                quantityInput.value = quantityInput.value.replace(',', '.');

                // Pastikan nilai minimal adalah 0.1
                if (parseFloat(quantityInput.value) < 0.1 || isNaN(parseFloat(quantityInput.value))) {
                    quantityInput.value = '';
                }

                // Pastikan nilai tidak melebihi stok maksimum
                let maxValue = unitSelect.value === 'gram' ? maxStock * 1000 : maxStock;
                if (parseFloat(quantityInput.value) > maxValue) {
                    quantityInput.value = maxValue;
                }
            });

            // Validasi akhir sebelum form dikirimkan
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const quantity = parseFloat(quantityInput.value);
                let maxValue = unitSelect.value === 'gram' ? maxStock * 1000 : maxStock;

                // Tampilkan pesan kesalahan jika nilai tidak valid
                if (isNaN(quantity) || quantity < 0.1 || quantity > maxValue) {
                    e.preventDefault();
                    alert(`Jumlah harus antara 0.1 dan ${maxValue.toFixed(3)} sesuai unit yang dipilih.`);
                    return;
                }

                // Jika unit adalah gram, ubah ke kg sebelum dikirimkan
                if (unitSelect.value === 'gram') {
                    quantityInput.value = (quantity / 1000).toFixed(3); // Konversi gram ke kg
                }
            });
        });
    </script>
@endsection
