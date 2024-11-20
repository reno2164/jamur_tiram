@extends('layouts.user')

@section('content')
    <style>
        /* Gaya untuk halaman produk */
        .product-detail {
            margin-top: 20px;
        }

        .product-images {
            position: relative;
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .thumbnail-images {
            display: flex;
            margin-top: 10px;
        }

        .thumbnail-images img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .thumbnail-images img.active {
            border-color: #007bff;
        }

        .price {
            font-size: 24px;
            color: #e53935;
            font-weight: bold;
        }

        .original-price {
            text-decoration: line-through;
            color: #9e9e9e;
            font-size: 16px;
        }

        .discount-badge {
            color: #fff;
            background-color: #ff5e57;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .stock-info {
            font-size: 14px;
            color: #757575;
            margin-top: 10px;
        }

        .btn-custom {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-cart {
            background-color: #ff5722;
            color: #fff;
        }
        .btn-warning {
            background-color: #ffef11;
            color: #fff;
        }

        .btn-buy {
            background-color: #007bff;
            color: #fff;
        }
    </style>

    <div class="container product-detail">
        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6 product-images">
                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->title }}" class="main-image">
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <h1>{{ $product->title }}</h1>
                <p>{{ $product->description }}</p>
                <div class="d-flex align-items-center">
                    <span class="price">Rp
                        {{ number_format($product->price - ($product->price * $product->discount) / 100) }}</span>
                    @if ($product->discount > 0)
                        <span class="original-price ms-3">Rp {{ number_format($product->price) }}</span>
                        <span class="discount-badge ms-3">-{{ $product->discount }}%</span>
                    @endif
                </div>
                <div class="stock-info">Stok tersedia: {{ $product->stok }} / Terjual: {{ $product->qty_out }}</div>

                <!-- Buttons -->
                <div class="mt-4">
                    @if (!Auth::check())
                        <a href="{{ route('login') }}" class="btn btn-cart btn-custom me-3">
                            <i class="fas fa-shopping-cart"></i> Masukkan Keranjang
                        </a>
                    @else
                    <form action="{{ route('addTocart') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="quantityInput" value="1">
                    
                        @if ($isInCart)
                            <!-- Jika produk sudah ada di keranjang, tampilkan tombol untuk menuju ke keranjang -->
                            <a href="{{ route('cart') }}" class="btn btn-warning btn-custom me-3">
                                <i class="fas fa-shopping-cart"></i> Menuju Ke Keranjang
                            </a>
                        @else
                            <!-- Jika produk belum ada di keranjang, tampilkan tombol "Masukkan Keranjang" -->
                            <button type="submit" class="btn btn-cart btn-custom me-3">
                                <i class="fas fa-shopping-cart"></i> Masukkan Keranjang
                            </button>
                        @endif
                    </form>
                    
                    @endif

                    <button class="btn btn-buy btn-custom"><i class="fas fa-money-check-alt"></i> Beli Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const thumbnails = document.querySelectorAll('.thumbnail-images img');
            const mainImage = document.querySelector('.main-image');
            const quantityInput = document.getElementById('quantity');
            const hiddenQuantityInput = document.getElementById('quantityInput');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    mainImage.src = this.src;

                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Update hidden quantity input based on visible input
            quantityInput.addEventListener('input', function() {
                hiddenQuantityInput.value = this.value;
            });
        });
    </script>
@endsection
