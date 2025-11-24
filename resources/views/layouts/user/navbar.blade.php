<nav class="navbar fixed-top navbar-expand-lg navbar-dark" style="background-color: #001B48;" data-aos="fade-down">
    <div class="container">
        <a class="navbar-brand font-semibold pe-none d-flex align-items-center" href="#">
            <img src="{{ asset('logo-tensitrack.png') }}" alt="TensiTrack" class="me-2" style="height: 35px; width: auto; object-fit: contain;">
            <span class="navbar-brand-text">TensiTrack</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0 font-medium nav-center">
                <li class="nav-item">
                    <a class="nav-link beranda" href="#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link alur-interaksi" href="#alur-interaksi">Alur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link kalkulator-bmi" href="#kalkulator-bmi">BMI</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link diagnosis" href="#diagnosis">Alur Kerja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link artikel" href="#artikel">Artikel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link faq" href="#faq">FAQ</a>
                </li>
            </ul>
            @if (Auth::check() && Gate::check('asUser'))
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal" class="nav-link" id="btnNavLinkProfile">
                            <div class="d-grid">
                                <button class="btn btn-masuk font-medium text-start">
                                    <i class="fa-solid fa-user pe-2"></i>
                                    Profil
                                </button>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" id="btnLogout" class="nav-link">
                            <div class="d-grid">
                                <button class="btn btn-masuk font-medium text-start">
                                    <i class="fa-solid fa-right-from-bracket pe-2"></i>
                                    Keluar
                                </button>
                            </div>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" id="formLogout" hidden
                            style="display: none">
                            @csrf
                        </form>
                    </li>
                    @push('scriptPerPage')
                        <script type="text/javascript">
                            const buttonLogout = document.getElementById('btnLogout');
                            buttonLogout.addEventListener('click', function(e) {
                                e.preventDefault();
                                document.getElementById('formLogout').submit();
                            });
                        </script>
                    @endpush
                </ul>
            @else
                <ul class="navbar-nav ms-auto ">
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">
                            <div class="d-grid">
                                <button class="btn btn-masuk font-medium text-start">
                                    <i class="fa-solid fa-right-to-bracket pe-2"></i>
                                    Masuk/Daftar
                                </button>
                            </div>
                        </a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>
