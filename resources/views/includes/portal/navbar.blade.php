<!-- ======= Header ======= -->
<header id="header" class="header fixed-top" data-scrollto-offset="0">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a href="{{ route('portal.index') }}" class="logo d-flex align-items-center justify-content-center scrollto me-auto me-lg-0" style="width: 3cm">
            <img src="{{ url('frontend/images/logo-no-background.png') }}" alt="" />
        </a>
        <div class="container-fluid d-flex align-items-center justify-content-center">
            <a class="nav-link scrollto" href="{{ url('#hero-animated') }}">Home</a>
            <a class="nav-link scrollto" href="{{ url('#about') }}">About</a>
            <a class="nav-link scrollto" href="{{ url('#faq') }}">FAQ</a>
        </div>
        @auth
        <a class="btn-dashborad scrollto text-center" href="{{ route('dashboard.index') }}" style="width: 3cm">Dashboard</a>
        @else
        <a class="btn-getstarted scrollto text-center" href="{{ route('login.index') }}" style="width: 2cm">Login</a>
        @endauth
    </div>
</header>
<!-- End Header -->