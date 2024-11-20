@extends('layouts.user')

@section('content')

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
                <img src="{{ asset('image/contoh3.jpg') }}" alt="Jamur Tiram 3" class="d-block w-100"
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
            @foreach ($best as $b)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <!-- Image Section -->
                        <div class="card-header p-0 position-relative">
                            <img src="{{ asset('storage/products/' . $b->image) }}" alt="{{ $b->title }}"
                                class="img-fluid" style="height:200px; object-fit: cover;">
                        </div>

                        <!-- Body Section -->
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold">{{ $b->title }}</h5>
                            <p class="text-muted m-0" style="font-size: 14px;">
                                <i class="fa-regular fa-star"></i> 5+ Reviews
                            </p>
                            <p class="text-primary m-0" style="font-size: 16px;">
                                <span>Rp {{ number_format($b->price) }}</span>
                            </p>

                            <!-- Stock Section -->
                            <p class="text-success m-0" style="font-size: 14px;">
                                Stok Tersedia: {{ $b->stok }}
                            </p>
                        </div>

                        <!-- Footer Section (Button) -->
                        <div class="card-footer bg-white text-center p-3 border-0">
                            <form action="{{ route('addTocart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $b->id }}">
                                <input type="hidden" name="title" value="{{ $b->title }}">
                                <input type="hidden" name="price" value="{{ $b->price }}">
                                <button type="submit" class="btn btn-primary w-100" style="font-size:16px;">
                                    <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
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
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <!-- Image Section -->
                        <div class="card-header p-0 position-relative">
                            <img src="{{ asset('storage/products/' . $p->image) }}" alt="{{ $p->title }}"
                                class="img-fluid" style="height:200px; object-fit: cover;">
                        </div>

                        <!-- Body Section -->
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold">{{ $p->title }}</h5>
                            <p class="text-muted m-0" style="font-size: 14px;">
                                <i class="fa-regular fa-star"></i> 5+ Reviews
                            </p>
                            <p class="text-primary m-0" style="font-size: 16px;">
                                <span>Rp {{ number_format($p->price) }}</span>
                            </p>

                            <!-- Stock Section -->
                            <p class="text-success m-0" style="font-size: 14px;">
                                Stok Tersedia: {{ $p->stok }}
                            </p>
                        </div>

                        <!-- Footer Section (Button) -->
                        <div class="card-footer bg-white text-center p-3 border-0">
                            <form action="{{ route('addTocart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="idProduct" value="{{ $p->id }}">
                                <button type="submit" class="btn btn-primary w-100" style="font-size:16px;">
                                    <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
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
