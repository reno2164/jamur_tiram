<!-- resources/views/layouts/main.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>
<body>
    <header>
        <h1>Pemilihan Pelanggan Terbaik</h1>
    </header>
    
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
