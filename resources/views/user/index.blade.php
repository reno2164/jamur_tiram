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
    <!-- Bagian Slideshow Gambar Hero -->
    <div id="heroCarousel" class="carousel slide mt-2" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('image/contoh.jpg') }}" alt="Jamur Tiram 1" class="d-block w-100"
                    style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Selamat Datang di Toko Jamur Tiram!</h1>
                    <p>Dapatkan jamur tiram segar dengan kualitas terbaik setiap hari.</p>
                    <a href="#product" class="btn btn-light btn-lg">Lihat Semua Produk</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('image/contoh2.jpg') }}" alt="Jamur Tiram 2" class="d-block w-100"
                    style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Jamur Tiram Segar Setiap Hari!</h1>
                    <p>Dapatkan berbagai produk olahan jamur tiram yang lezat.</p>
                    <a href="#product" class="btn btn-light btn-lg">Lihat Semua Produk</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('image/wkwk.gif') }}" alt="Jamur Tiram 3" class="d-block w-100"
                    style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Menikmati Jamur Tiram dengan Resep Lezat!</h1>
                    <p>Ikuti resep kami dan nikmati hidangan yang menggugah selera.</p>
                    <a href="#product" class="btn btn-light btn-lg">Lihat Semua Produk</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    @if ($best->count() == 0)
        <div class="container"></div>
    @else
        <h4 class="mt-5">Best Seller</h4>
        <div class="content mt-3 d-flex flex-lg-wrap gap-5 mb-5">
            @foreach ($best as $p)
                <div class="col-md-3 mb-4">
                    <div class="product-card position-relative">
                        <!-- Cek stok produk -->
                        @if ($p->stok > 0)
                            <a href="{{ route('product.detail', $p->id) }}" class="text-decoration-none text-dark">
                            @else
                                <span class="text-decoration-none text-dark">
                        @endif
                        <!-- Discount Badge -->
                        @if ($p->discount > 0)
                            <div class="badge-discount">-{{ $p->discount }}%</div>
                        @endif

                        <!-- Product Image -->
                        <img src="{{ asset('storage/products/' . $p->image) }}" alt="{{ $p->title }}">

                        <!-- Product Info -->
                        <div class="product-info">
                            <div class="product-title">{{ $p->title }}</div>
                            <div class="product-price">
                                Rp {{ number_format($p->price - ($p->price * $p->discount) / 100) }}
                            </div>
                            @if ($p->discount > 0)
                                <div class="product-original-price">Rp {{ number_format($p->price) }}</div>
                            @endif
                            @if ($p->stok > 0)
                                <p class="text-success">Tersedia: {{ $p->stok }} Kg</p>
                            @else
                                <p class="text-danger">Habis</p>
                            @endif

                            <!-- Ratings and Sales -->
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="product-rating">
                                    <i class="fas fa-star"></i>
                                    <span>4.7</span> <!-- Example rating -->
                                </div>
                                <div>{{ $p->qty_out }} Terjual</div>
                            </div>
                        </div>
                        @if ($p->stok > 0)
                            </a>
                        @else
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h4 class="mt-5" id="product">Product</h4>
    <div class="content mt-3 d-flex flex-lg-wrap gap-5 mb-5">
        @if ($data->isEmpty())
            <h1>Belum ada produk ...!</h1>
        @else
            @foreach ($data as $p)
                <div class="col-md-3 mb-4">
                    <div class="product-card position-relative">
                        <!-- Cek stok produk -->
                        @if ($p->stok > 0)
                            <a href="{{ route('product.detail', $p->id) }}" class="text-decoration-none text-dark">
                            @else
                                <span class="text-decoration-none text-dark">
                        @endif
                        <!-- Discount Badge -->
                        @if ($p->discount > 0)
                            <div class="badge-discount">-{{ $p->discount }}%</div>
                        @endif

                        <!-- Product Image -->
                        <img src="{{ asset('storage/products/' . $p->image) }}" alt="{{ $p->title }}">

                        <!-- Product Info -->
                        <div class="product-info">
                            <div class="product-title">{{ $p->title }}</div>
                            <div class="product-price">
                                Rp {{ number_format($p->price - ($p->price * $p->discount) / 100) }}
                            </div>
                            @if ($p->discount > 0)
                                <div class="product-original-price">Rp {{ number_format($p->price) }}</div>
                            @endif
                            @if ($p->stok > 0)
                                <p class="text-success">Tersedia: {{ $p->stok }} Kg</p>
                            @else
                                <p class="text-danger">Habis</p>
                            @endif

                            <!-- Ratings and Sales -->
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="product-rating">
                                    <i class="fas fa-star"></i>
                                    <span>4.7</span> <!-- Example rating -->
                                </div>
                                <div>{{ $p->qty_out }} Terjual</div>
                            </div>
                        </div>
                        @if ($p->stok > 0)
                            </a>
                        @else
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
    </div>
    <div class="pagination d-flex flex-row justify-content-between">
        <div class="showData">
            Data ditampilkan {{ $data->count() }} dari {{ $data->total() }}
        </div>
        <div>
            {{ $data->links() }}
        </div>
    </div>
    @endif

    <!-- Testimonial Pelanggan -->
    <h4 class="mt-5">Testimonial Pelanggan</h4>
    <div class="content mt-3 d-flex flex-lg-wrap gap-5 mb-5">
        <div class="card" style="width:300px;">
            <div class="card-body">
                <p>"Jamur tiram di sini selalu segar dan kualitasnya sangat baik. Saya sangat puas!"</p>
                <p class="text-muted">- Intan, Pelanggan Setia</p>
            </div>
        </div>
        <div class="card" style="width:300px;">
            <div class="card-body">
                <p>"Pengirimannya cepat dan produk yang diterima dalam kondisi baik. Harganya pun terjangkau!"</p>
                <p class="text-muted">- Indah, Pelanggan Baru</p>
            </div>
        </div>
        <div class="card" style="width:300px;">
            <div class="card-body">
                <p>"Saya suka belanja di sini! Banyak pilihan produk jamur dan pelayanan yang ramah."</p>
                <p class="text-muted">- Reno, Penggemar Masakan Jamur</p>
            </div>
        </div>
    </div>

    <!-- Informasi Tentang Jamur Tiram -->
    <h4 class="mt-5">Kenali Jamur Tiram</h4>
    <div class="content mt-3">
        <p>Jamur tiram merupakan salah satu jenis jamur yang banyak digemari karena cita rasanya yang lezat dan kaya akan
            nutrisi. Jamur ini sangat cocok diolah menjadi berbagai hidangan, baik sebagai bahan utama maupun pelengkap.</p>
        <p>Di toko kami, kami menyediakan jamur tiram segar yang dipetik langsung dari petani. Selain itu, kami juga
            menawarkan berbagai produk olahan jamur tiram yang siap saji.</p>
    </div>

@endsection
