@extends('layouts.user')

@section('content')
    <!-- Hero Section -->
    <div class="container-fluid text-white py-5 mb-5 mt-2"
        style="background-image: url({{ asset('image/contoh4.jpg') }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1 class="display-4 text-center" style="color: #f4e7d4;">Kontak Kami</h1>
            <p class="lead text-center" style="color: #f4e7d4;">Kami siap mendengar masukan, kritik, dan saran dari Anda</p>
        </div>
    </div>

    <!-- Info Kontak -->
    <div class="container my-5">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card p-4 shadow" style="border: none; background-color: #f4e7d4;">
                    <i class="fa fa-phone fa-3x text-secondary mb-3"></i>
                    <h5 style="color: #49443a;">Telepon</h5>
                    <p>+62 812-6776-9742</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 shadow" style="border: none; background-color: #f4e7d4;">
                    <i class="fa fa-map-marker-alt fa-3x text-secondary mb-3"></i>
                    <h5 style="color: #49443a;">Alamat</h5>
                    <p>Jl A. Yani No.08, RT.01/RW.01, Tirta Jaya, Kec.<br>Bajuin, Kabupaten Tanah Laut</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 shadow" style="border: none; background-color: #f4e7d4;">
                    <i class="fa fa-envelope fa-3x text-secondary mb-3"></i>
                    <h5 style="color: #49443a;">Email</h5>
                    <p>jamurtiram@gmail.com</p>
                </div>
            </div>
        </div>
    </div>
    <div style="width: 100%; height:50vh; border-radius:10px; overflow: hidden;">
        <!-- Peta Lokasi Google -->
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1990.5285450732035!2d114.81938921649383!3d-3.797721558952971!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de6f71c1bf0ad9f%3A0xb6d07277c850384a!2sTIRAM%20TIRTA%20(Budidaya%20Jamur%20Tiram)!5e0!3m2!1sid!2sid!4v1729074151440!5m2!1sid!2sid"
            width="100%" height="100%" style="border:0; border-radius:10px;" allowfullscreen="" loading="lazy"></iframe>
    </div>

    <!-- Testimoni Pelanggan -->
    <div class="container text-center my-5">
        <h4 style="color: #49443a;">Testimoni Pelanggan</h4>
        <hr class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4 shadow" style="border: none; background-color: #f4e7d4;">
                    <i class="fa fa-user-circle fa-3x mb-3 text-secondary"></i>
                    <p>"Pelayanan sangat memuaskan, jamur tiramnya segar dan pengiriman cepat!"</p>
                    <p style="font-weight: bold;">- Intan, Pelaihari</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 shadow" style="border: none; background-color: #f4e7d4;">
                    <i class="fa fa-user-circle fa-3x mb-3 text-secondary"></i>
                    <p>"Harga kompetitif dan produk berkualitas. Sangat puas dengan pembelian di sini."</p>
                    <p style="font-weight: bold;">- Indah, Bajuin</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 shadow" style="border: none; background-color: #f4e7d4;">
                    <i class="fa fa-user-circle fa-3x mb-3 text-secondary"></i>
                    <p>"Rekomendasi banget! Jamur tiramnya segar dan enak untuk dimasak."</p>
                    <p style="font-weight: bold;">- Reno, Angsau</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer CTA -->
    <div class="container-fluid py-5" style="background-color: #49443a;">
        <div class="container text-center text-white">
            <h2>Butuh Bantuan?</h2>
            <p>Hubungi kami untuk mendapatkan informasi lebih lanjut tentang produk jamur tiram kami.</p>
            <a href="mailto:jamurtiram@gmail.com" class="btn" style="background-color: #f4e7d4; color: #49443a;">Hubungi
                Kami Sekarang</a>
        </div>
    </div>
@endsection
