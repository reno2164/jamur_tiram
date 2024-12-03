<style>
    /* Container utama */
    .select {
        position: relative;
        cursor: pointer;
    }

    /* Profil User */
    .profile-img {
        width: 45px;
        height: 45px;
        object-fit: cover;
    }

    .profile-info {
        color: #ffffff;
    }

    .username {
        font-weight: 600;
        font-size: 14px;
    }

    .email {
        font-size: 12px;
        color: #e0e0e0;
    }

    /* Dropdown menu */
    #links-login {
        position: absolute;
        top: 60px;
        right: 0;
        background-color: #2c2c2c;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        padding: 10px;
        width: 200px;
        display: none;
        z-index: 10;
    }

    #links-login.activeLogin {
        display: block;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .dropdown-item:hover {
        background-color: #494949;
        border-radius: 5px;
    }

    .dropdown-item i {
        color: #6c63ff;
        font-size: 16px;
    }

    /* Tombol Logout */
    .btn-logout {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        border: none;
        background: #e53935;
        color: #fff;
        padding: 10px;
        font-size: 14px;
        text-align: left;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-logout:hover {
        background-color: #c62828;
    }

    .btn-logout i {
        color: #fff;
        font-size: 16px;
    }
</style>

<nav class="navbar navbar-dark navbar-expand-lg" style="background-color: #49443a">
<div class="container">
    <a class="navbar-brand fs-6" href="/">Jamur Tiram <br>Putra Pandawa</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end gap-4" id="navbarSupportedContent">
        <ul class="navbar-nav gap-4">
            <li class="nav-item my-auto">
                <a class="nav-link {{ Request::path() == '/' ? 'active' : '' }}" aria-current="page" href="/">Beranda</a>
            </li>
            <li class="nav-item my-auto">
                <a class="nav-link {{ Request::path() == 'shop' ? 'active' : '' }}" href="/shop">Belanja</a>
            </li>
            <li class="nav-item my-auto">
                <a class="nav-link {{ Request::path() == 'contact' ? 'active' : '' }}" href="/contact">Kontak</a>
            </li>
            <li class="nav-item my-auto">
                <div class="notif">
                    <a href="{{ route('riwayat') }}" class="fs-5 nav-link {{ Request::path() == 'riwayat-pembelian' ? 'active' : '' }}">
                        <i class="fa-regular fa-bell"></i>
                    </a>
                </div>
            </li>
            <li class="nav-item my-auto">
                <div class="notif">
                    <a href="{{ route('cart') }}" class="fs-5 nav-link {{ Request::path() == 'keranjang' ? 'active' : '' }}">
                        <i class="fa-solid fa-cart-plus"></i>
                    </a>
                    @if ($count)
                        <div class="circle">{{ $count }}</div>
                    @endif
                </div>
            </li>
            @auth
                <div class="select">
                    <div class="text-links">
                        <div class="d-flex gap-2 align-items-center">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7gTERsv3nO-4I-R9C00Uor_m_nmxT0sE9Cg&s" 
                                class="rounded-circle profile-img" alt="Profile Image">
                            <div class="d-flex flex-column profile-info">
                                <p class="m-0 username">{{ Auth::user()->username }}</p>
                                <p class="m-0 email">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="links-login" id="links-login">
                        @if (Auth::user()->role == 'ADM')
                            <a href="{{ route('admin.index') }}" class="dropdown-item">
                                <i class="fa-solid fa-chart-line"></i> Dashboard Admin
                            </a>
                        @elseif (Auth::user()->role == 'PGW')
                            <a href="{{ route('pegawai.index') }}" class="dropdown-item">
                                <i class="fa-solid fa-clipboard-list"></i> Dashboard Pegawai
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fa-solid fa-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <li class="nav-item my-auto">
                    <a href="/login" class="btn btn-outline-light">Masuk</a>
                </li>
            @endauth
        </ul>
    </div>
</div>
</nav>

<script>
document.addEventListener("click", function (e) {
    const dropdown = document.querySelector(".select");
    const linksLogin = document.querySelector("#links-login");
    if (dropdown.contains(e.target)) {
        // Klik pada dropdown, toggle kelas aktif
        linksLogin.classList.toggle("activeLogin");
    } else {
        // Klik di luar dropdown, tutup dropdown
        linksLogin.classList.remove("activeLogin");
    }
});
</script>