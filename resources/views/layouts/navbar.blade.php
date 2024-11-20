
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
                    <a class="nav-link {{ Request::path() == '/' ? 'active' : '' }}" aria-current="page"
                        href="/">Beranda</a>
                </li>
                <li class="nav-item my-auto">
                    <a class="nav-link {{ Request::path() == 'shop' ? 'active' : '' }}" href="/shop">Belanja</a>
                </li>
                <li class="nav-item my-auto">
                    <a class="nav-link {{ Request::path() == 'contact' ? 'active' : '' }}" href="/contact">Kontak
                        </a>
                </li>
                <li class="nav-item my-auto">
                    <div class="notif">
                        <a href="#" class="fs-5 nav-link {{ Request::path() == 'checkOut' ? 'active' : '' }}">
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
                    
                    <div class="select" tabindex="0" role="button">
                        <div class="text-links">
                            <div class="d-flex gap-2 align-items-center">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7gTERsv3nO-4I-R9C00Uor_m_nmxT0sE9Cg&s" class="rounded-circle"
                                    style="width: 40px;" alt="">
                                <div class="d-flex flex-column text-white">
                                    <p class="m-0" style="font-weight: 700; font-size:14px;">{{ Auth::user()->username }}
                                    </p>
                                    <p class="m-0" style="font-size:12px">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="links-login text-white" id="links-login">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" style="text-decoration: none" class="" role="button" tabindex="0">Keluar</button>
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
    $(".text-links").click(function(e) {
        e.preventDefault();
        var $linksLogin = $("#links-login");
        if ($linksLogin.hasClass("activeLogin")) {
            $linksLogin.removeClass("activeLogin");
        } else {
            $linksLogin.addClass("activeLogin");
        }
    });
</script>
