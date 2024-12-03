@extends('layouts.user')

@section('content')

<!-- Hero Section -->
<div class="container-fluid text-white py-5 mb-5 mt-2" 
    style="background-image: url({{asset('image/contoh4.jpg')}}); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container">
        <h1 class="display-4 text-center">Kontak Kami</h1>
        <p class="lead text-center">Kami siap mendengar masukan, kritik, dan saran dari Anda</p>
    </div>
</div>

<!-- Info Kontak -->
<div class="row text-center my-5">
    <div class="col-md-4">
        <i class="fa fa-phone fa-3x text-primary mb-3"></i>
        <h5>Telepon</h5>
        <p>+62 812-6776-9742</p>
    </div>
    <div class="col-md-4">
        <i class="fa fa-envelope fa-3x text-primary mb-3"></i>
        <h5>Email</h5>
        <p>jamurtiram@gmail.com</p>
    </div>
    <div class="col-md-4">
        <i class="fa fa-map-marker-alt fa-3x text-primary mb-3"></i>
        <h5>Alamat</h5>
        <p>Jl A. Yani No.08, RT.01/RW.01, Tirta Jaya, Kec. <br> Bajuin, Kabupaten Tanah Laut</p>
    </div>
</div>

<!-- Kritik dan Saran Section -->
<div class="row mb-md-5">
    <div class="col-md-6">
        <div class="bg-secondary" style="width: 100%; height:50vh; border-radius:10px;">
            <!-- Peta Lokasi Google -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1990.5285450732035!2d114.81938921649383!3d-3.797721558952971!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de6f71c1bf0ad9f%3A0xb6d07277c850384a!2sTIRAM%20TIRTA%20(Budidaya%20Jamur%20Tiram)!5e0!3m2!1sid!2sid!4v1729074151440!5m2!1sid!2sid" 
                width="100%" height="100%" style="border:0; border-radius:10px;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header text-center bg-primary text-white">
                <h4>Kritik dan Saran</h4>
            </div>
            <div class="card-body">
                <p class="p-0 mb-4 text-lg-center">Kami sangat menghargai masukan dan saran Anda untuk perbaikan layanan kami.</p>
                <form action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukan Nama Anda" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukan Email Anda" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Masukan Pesan Anda" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Testimoni Pelanggan -->
<h4 class="text-center mt-5">Testimoni Pelanggan</h4>
<hr class="mb-4">
<div class="row text-center">
    <div class="col-md-4">
        <div class="card p-3">
            <i class="fa fa-user-circle fa-3x mb-3 text-primary"></i>
            <p>"Pelayanan sangat memuaskan, jamur tiramnya segar dan pengiriman cepat!"</p>
            <p>- Intan, Pelaihari</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <i class="fa fa-user-circle fa-3x mb-3 text-primary"></i>
            <p>"Harga kompetitif dan produk berkualitas. Sangat puas dengan pembelian di sini."</p>
            <p>- Indah, Bajuin</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <i class="fa fa-user-circle fa-3x mb-3 text-primary"></i>
            <p>"Rekomendasi banget! Jamur tiramnya segar dan enak untuk dimasak."</p>
            <p>- Reno, Angsau</p>
        </div>
    </div>
</div>


<div class="container-fluid bg-primary text-white py-5 mt-5">
    <div class="container text-center">
        <h2>Butuh Bantuan?</h2>
        <p>Hubungi kami untuk mendapatkan informasi lebih lanjut tentang produk jamur tiram kami.</p>
        <a href="mailto:jamurtiram@gmail.com" class="btn btn-light">Hubungi Kami Sekarang</a>
    </div>
</div>

@endsection
