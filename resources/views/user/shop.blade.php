@extends('layouts.user')

@section('content')
    <style>
        /* Styling for product cards */
        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #ffffff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Discount Badge */
        .badge-discount {
            background-color: #ff5e57;
            color: white;
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 12px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 20px;
        }

        /* Image Styling */
        .product-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        /* Product Info */
        .product-info {
            padding: 15px;
        }

        .product-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 18px;
            font-weight: bold;
            color: #e53935;
            margin-bottom: 5px;
        }

        .product-original-price {
            font-size: 14px;
            color: #9e9e9e;
            text-decoration: line-through;
        }

        .product-location {
            font-size: 12px;
            color: #757575;
            margin-top: 10px;
        }

        .product-rating {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #fbc02d;
        }

        .product-rating span {
            margin-left: 5px;
            color: #333;
        }
    </style>

    <div class="container mt-4">
        <div class="row">
            @if ($data->isEmpty())
                <div class="col-12">
                    <h1 class="text-center">Belum ada produk ...!</h1>
                </div>
            @else
                @foreach ($data as $p)
                    <div class="col-md-3 mb-4">
                        <div class="product-card position-relative">
                            <a href="{{ route('product.detail', $p->id) }}" class="text-decoration-none text-dark">
                            <!-- Discount Badge -->
                            @if ($p->discount > 0)
                                <div class="badge-discount">-{{ $p->discount }}%</div>
                            @endif

                            <!-- Product Image -->
                            <img src="{{ asset('storage/products/' . $p->image) }}" alt="{{ $p->title }}">

                            <!-- Product Info -->
                            <div class="product-info">
                                <div class="product-title">{{ $p->title }}</div>
                                <div class="product-price">Rp {{ number_format($p->price - ($p->price * $p->discount / 100)) }}</div>
                                @if ($p->discount > 0)
                                    <div class="product-original-price">Rp {{ number_format($p->price) }}</div>
                                @endif
                                <p class="text-success">Tersedia : {{ $p->stok }} Kg</p>
                                <!-- Ratings and Sales -->
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="product-rating">
                                        <i class="fas fa-star"></i>
                                        <span>4.7</span> <!-- Example rating -->
                                    </div>
                                    <div>{{ $p->qty_out }} Terjual</div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-between mt-5">
            <div class="showData">
                Menampilkan {{ $data->count() }} dari total {{ $data->total() }} produk
            </div>
            <div>
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
