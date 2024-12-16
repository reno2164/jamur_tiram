<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>
    <!-- Tambahkan link ke file CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet"> <!-- Tambahkan file CSS admin -->
</head>
<body>
    <header class="bg-primary text-white p-3">
        <h1 class="text-center">Pannel Admin</h1>
    </header>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-dark text-white vh-100" style="width: 250px;">
            <div class="p-3">
                <h3 class="text-center">ADMIN</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Product</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Pelanggan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Data Penjualan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <header class="p-3 bg-light border-bottom">
                <h4>@yield('title', 'Dashboard Admin')</h4>
            </header>
            <main class="container mt-4">
                @yield('content') <!-- Tempat untuk konten halaman -->
            </main>
        </div>
    </div>

    <footer class="bg-light text-center p-3 mt-4">
        <p>&copy; 2024 - Pannel Admin</p>
    </footer>

    <!-- Tambahkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
