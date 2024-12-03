<section>
    {{ $name }}
</section>
<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">
    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" 
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        </a>
    </li>

    @auth
    <!-- User Profile Dropdown -->
    <div class="dropdown">
        <div class="d-flex align-items-center gap-2" data-toggle="dropdown" role="button" tabindex="0">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7gTERsv3nO-4I-R9C00Uor_m_nmxT0sE9Cg&s" 
                 class="rounded-circle profile-img" alt="Profile Image" style="width: 40px; height: 40px;">
            <div>
                <p class="m-0 font-weight-bold">{{ Auth::user()->username }}</p>
                <p class="m-0 text-muted small">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <div class="dropdown-menu dropdown-menu-right" id="links-login">
            @if (Auth::user()->role == 'ADM')
                <a href="{{ route('admin.index') }}" class="dropdown-item">
                    <i class="fa-solid fa-chart-line"></i> Dashboard Admin
                </a>
            @elseif (Auth::user()->role == 'PGW')
                <a href="{{ route('pegawai.index') }}" class="dropdown-item">
                    <i class="fa-solid fa-clipboard-list"></i> Dashboard Pegawai
                </a>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="dropdown-item p-0 m-0">
                @csrf
                <button type="submit" class="btn btn-link text-danger btn-sm w-100 text-left">
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

