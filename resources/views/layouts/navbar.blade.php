<style>
    /* Gaya Dropdown Profil */
.profile-img {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #fff;
}

.username {
    font-weight: bold;
    font-size: 14px;
    color: #ffffff;
}

.email {
    font-size: 12px;
    color: #d4d4d4;
}

/* Menu Dropdown */
/* Gaya Profil Dropdown */
.profile-img {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 50%;
    border: 0px solid #fff;
}

.username {
    font-weight: 600;
    font-size: 14px;
    color: #ffffff;
}

.email {
    font-size: 12px;
    color: #ffffff;
}

/* Dropdown Menu Styling */
.dropdown-menu {
    border-radius: 12px;
    padding: 8px 0;
    background-color: #f8f9fa;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    border: none;
}

.dropdown-item {
    font-size: 14px;
    padding: 10px 16px;
    transition: background-color 0.3s, color 0.3s;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 10px;
}

.dropdown-item i {
    font-size: 18px;
}

.dropdown-item:hover {
    background-color: #e9ecef;
    color: #212529;
}

.dropdown-divider {
    margin: 4px 0;
}

/* Tombol Logout */
.dropdown-item.text-danger:hover {
    background-color: #f8d7da;
    color: #dc3545;
}


/* Notifikasi Badge */
.notif .circle {
    position: absolute;
    top: 0;
    right: 0;
    transform: translate(50%, -50%);
    background: #ff1616;
    color: #fff;
    font-size: 12px;
    border-radius: 50%;
    padding: 0px 8px;
    font-weight: bold;
    margin: 8px;
}

/* Tombol Logout */
.btn-logout {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    background: #e53935;
    color: #fff;
    padding: 10px;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-logout:hover {
    background-color: #d32f2f;
}

</style>

<nav class="navbar navbar-dark navbar-expand-lg" style="background-color: #49443a;">
    <div class="container">
        <a class="navbar-brand fs-5" href="/">Jamur Tiram <br>Putra Pandawa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end gap-4" id="navbarSupportedContent">
            <ul class="navbar-nav gap-4">
                <!-- Link Navigasi -->
                <li class="nav-item my-auto">
                    <a class="nav-link {{ Request::path() == '/' ? 'active' : '' }}" aria-current="page" href="/">Beranda</a>
                </li>
                <li class="nav-item my-auto">
                    <a class="nav-link {{ Request::path() == 'shop' ? 'active' : '' }}" href="/shop">Belanja</a>
                </li>
                <li class="nav-item my-auto">
                    <a class="nav-link {{ Request::path() == 'kontak' ? 'active' : '' }}" href="/kontak">Kontak</a>
                </li>
                <li class="nav-item my-auto notif">
                    <a class="nav-link position-relative {{ Request::path() == 'pesanan' ? 'active' : '' }}" href="/pesanan">
                        Pesanan
                        <span class="circle">{{ $count }}</span>
                    </a>
                </li>

                <!-- Notifikasi -->
                <li class="nav-item my-auto notif">
                    <a href="#" class="fs-5 nav-link position-relative">
                        <i class="fa-regular fa-bell"></i>
                        {{-- <span class="circle">3</span> --}}
                    </a>
                </li>

                <!-- Keranjang Belanja -->
                <li class="nav-item my-auto notif">
                    <a href="{{ route('cart') }}" class="fs-5 nav-link position-relative">
                        <i class="fa-solid fa-cart-plus"></i>
                        @if ($count)
                            <span class="circle">{{ $count }}</span>
                        @endif
                    </a>
                </li>

                <!-- Dropdown Profil -->
                @auth
                <li class="nav-item dropdown">
                    <div class="nav-link dropdown-toggle d-flex align-items-center gap-2"  role="button"
                        id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- Profil Gambar -->
                        <img src="{{ Auth::user()->profile_image ?? 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7gTERsv3nO-4I-R9C00Uor_m_nmxT0sE9Cg&s' }}"
                            class="rounded-circle profile-img" alt="Profile Image">
                        <!-- Informasi User -->
                        <div class="text-start">
                            <p class="m-0 username">{{ Auth::user()->username }}</p>
                            <small class="email">{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                
                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="profileDropdown">
                        <!-- Role-Based Navigation -->
                        @if (Auth::user()->role === 'ADM')
                            <li>
                                <a href="{{ route('admin.index') }}" class="dropdown-item d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-chart-line text-primary"></i>
                                    <span>Dashboard Admin</span>
                                </a>
                            </li>
                        @elseif (Auth::user()->role === 'PGW')
                            <li>
                                <a href="{{ route('admin.index') }}" class="dropdown-item d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-clipboard-list text-info"></i>
                                    <span>Dashboard Pegawai</span>
                                </a>
                            </li>
                        @endif
                
                        <!-- Separator -->
                        <li><hr class="dropdown-divider"></li>
                
                        <!-- Tombol Logout -->
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                
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
    document.addEventListener("click", function(e) {
        const dropdown = document.querySelector(".select");
        const linksLogin = document.querySelector("#links-login");
        if (dropdown.contains(e.target)) {
            linksLogin.classList.toggle("activeLogin");
        } else {
            linksLogin.classList.remove("activeLogin");
        }
    });
</script>
