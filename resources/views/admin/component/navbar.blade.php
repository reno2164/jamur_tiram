<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">
    <!-- Menu TPK dan Hasil TPK -->
    <li class="nav-item">
        <a class="nav-link" href="/tpk">
            <i class="fas fa-cogs"></i> TPK
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/hasil-tpk">
            <i class="fas fa-chart-bar"></i> Hasil TPK
        </a>
    </li>

    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <!-- Counter - Alerts -->
            <span class="badge badge-danger badge-counter">3+</span>
        </a>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->email }}<br>{{ Auth::user()->username }}</span>
            <img class="img-profile rounded-circle"
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7gTERsv3nO-4I-R9C00Uor_m_nmxT0sE9Cg&s">
        </a>
    </li>
</ul>

