<div class="">
    <ul class="navbar-nav bg-black sidebar sidebar-dark accordion px-2" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <div class="sidebar-brand d-flex align-items-center justify-content-center">
            <a href="/"><</a>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('admin.index')}}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin</div>
            </a>
        </div>

        <hr class="sidebar-divider my-0">

        <li class="nav-item active {{ Request::path() === 'admin' ? 'badge text-bg-info' : '' }}">
            <a class="nav-link" href="{{ route('admin.index') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item active {{ Request::path() === 'admin/product' ? 'badge text-bg-info' : '' }}">
            <a class="nav-link collapsed" href="{{ route('product.index') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span>Product</span>
            </a>
        </li>
    
        <li class="nav-item active {{ Request::path() === 'admin/users' ? 'badge text-bg-info' : ''}}">
            <a class="nav-link collapsed" href="{{ route('manage.users') }}">
                <i class="fas fa-fw fa-solid fa-users"></i>
                <span>User Management</span>
            </a>
        </li>
        
    
        <li class="nav-item active {{ Request::path() === 'admin/pesanan' ? 'badge text-bg-info' : ''}}">
            <a class="nav-link collapsed" href="{{ route('admin.pesanan') }}" >
                <i class="fas fa-fw fa-solid fa-file-invoice-dollar"></i>
                <span>Pesanan</span>
            </a>
        </li>

        <li class="nav-item active {{ Request::path() === 'admin/DataPenjualan' ? 'badge text-bg-info' : '' }}">
            <a class="nav-link" href="{{ route('admin.datapenjualan') }}">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Data Penjualan</span>
            </a>
        </li>
        @if (Auth::user()->role == 'ADM')
        <!-- Menu SAW -->
        <li class="nav-item active {{ Request::path() === 'admin/tpk' ? 'badge text-bg-info' : '' }}">
            <a class="nav-link collapsed" href="{{ route('spk.saw.index') }}">
                <i class="fas fa-fw fa-chart-bar"></i>
                <span>TPK</span>
            </a>
        </li>
        @endif


        <!-- Menu Hasil TPK -->
        <li class="nav-item active {{ Request::path() === 'hasil-tpk' ? 'badge text-bg-info' : '' }}">
            <a class="nav-link collapsed" href="{{ route('hasil.tpk.index') }}">
                <i class="fas fa-fw fa-trophy"></i>
                <span>Hasil TPK</span>
            </a>

        <li class="nav-item active">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link collapsed">
                    <span class="material-symbols-outlined">
                        logout
                    </span>
                    <span>Logout</span>
                </button>
            </form>

        </li>
    </ul>
</div>
