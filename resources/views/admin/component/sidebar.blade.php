<div>
    <ul class="navbar-nav bg-black sidebar sidebar-dark accordion px-2" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <div class="sidebar-brand d-flex align-items-center justify-content-center">
            <a href="/"><-</a>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('admin.index')}}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin</div>
            </a>
        </div>
    
        <hr class="sidebar-divider my-0">
        
        <li class="nav-item active {{ Request::path() === 'admin' ? 'badge text-bg-info' : '' }}">
            <a class="nav-link" href="{{route('admin.index')}}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
    
        <li class="nav-item active {{ Request::path() === 'admin/product' ? 'badge text-bg-info' : '' }}">
            <a class="nav-link collapsed" href="{{ route('product.index') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span>Product</span>
            </a>
        </li>
    
        <li class="nav-item active {{ Request::path() === 'admin/pelanggan' ? 'badge text-bg-info' : ''}}">
    
            <a class="nav-link collapsed" href="{{--route('pelanggan.index')--}}" >
                <i class="fas fa-fw fa-solid fa-users"></i>
                <span>Pelanggan</span>
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
                <span>Data Penjualan</span></a>
        </li>
    
        <li class="nav-item active">
            <a class="nav-link collapsed" href="#" >
                <span class="material-symbols-outlined">
                    logout
                    </span>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>