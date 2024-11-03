<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }} parent" href="{{ route('dashboard.index') }}">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-home"></i>
                    </div>
                    <b>Dashboard</b>
                </a>
                @can('admin')
                {{-- Dropdown Master Data --}}
                <a href="#" class="nav-link collapsed {{ Request::is('dashboard/data/*') ? 'active' : '' }} parent" data-bs-toggle="collapse" data-bs-target="#masterDataCollapse" aria-expanded="false">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <b>Master Data</b>
                    <i class="fas fa-caret-down ms-auto"></i>
                </a>
                <div class="collapse {{ Request::is('dashboard/data/*') ? 'show' : '' }}" id="masterDataCollapse" data-bs-parent="#sidenavAccordion">
                    <a class="nav-link {{ Request::is('dashboard/data/kriteria*') ? 'active' : '' }} child" href="{{ route('kriteria.index') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Kriteria
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/data/wisata*') ? 'active' : '' }} child" href="{{ route('wisata.index') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Destinasi Wisata
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/data/jenis*') ? 'active' : '' }} child" href="{{ route('jenis.index') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Jenis Wisata
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/data/alternatif*') ? 'active' : '' }} child" href="{{ route('alternatif.index') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Alternatif
                    </a>
                </div>
                {{-- Dropdown Saran --}}
                <a href="#" class="nav-link collapsed {{ Request::is('dashboard/sarans*') ? 'active' : '' }} parent" data-bs-toggle="collapse" data-bs-target="#masterDataCollapse1" aria-expanded="false">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <b>Master Saran</b>
                    <i class="fas fa-caret-down ms-auto"></i>
                </a>
                <div class="collapse {{ Request::is('dashboard/sarans*') ? 'show' : '' }}" id="masterDataCollapse1" data-bs-parent="#sidenavAccordion">
                    <a class="nav-link {{ Request::is('dashboard/sarans*') ? 'active' : '' }} child" href="{{ route('sarans.index') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Validasi Saran Wisata
                    </a>
                </div>
                {{-- Dropdown Master SPK --}}
                <a href="#" class="nav-link collapsed {{ Request::is('dashboard/perhitungan*') ? 'active' : '' }} parent" data-bs-toggle="collapse" data-bs-target="#masterDataCollapse2" aria-expanded="false">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-ranking-star"></i>
                    </div>
                    <b>Master SPK</b>
                    <i class="fas fa-caret-down ms-auto"></i>
                </a>
                <div class="collapse {{ Request::is('dashboard/perhitungan*') ? 'show' : '' }}" id="masterDataCollapse2" data-bs-parent="#sidenavAccordion">
                    <a class="nav-link {{ Request::is('dashboard/perhitungan/metode*') ? 'active' : '' }} child" href="{{ route('kombinasi.awal') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Metode SPK
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/perhitungan/setting*') ? 'active' : '' }} child" href="{{ route('kombinasi.index') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Perhitungan
                    </a>
                </div>
                {{-- Dropdown Master Pengguna --}}
                <a href="#" class="nav-link collapsed {{ Request::is('dashboard/pengguna*') ? 'active' : '' }} parent" data-bs-toggle="collapse" data-bs-target="#masterDataCollapse3" aria-expanded="false">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-user-gear"></i>
                    </div>
                    <b>Master Pengguna</b>
                    <i class="fas fa-caret-down ms-auto"></i>
                </a>
                <div class="collapse {{ Request::is('dashboard/pengguna*') ? 'show' : '' }}" id="masterDataCollapse3" data-bs-parent="#sidenavAccordion">
                    <a class="nav-link {{ Request::is('dashboard/pengguna/users*') ? 'active' : '' }} child" href="{{ route('users.index') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Pengguna
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/pengguna/profile*') ? 'active' : '' }} child" href="{{ route('profile.index') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Ubah Profil
                    </a>
                </div>
                @elseif('user')
                <a class="nav-link collapsed {{ Request::is('dashboard/sarans*') ? 'active' : '' }} parent" href="{{ route('sarans.index') }}">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    Saran Destinasi Wisata
                </a>
                <a class="nav-link collapsed {{ Request::is('dashboard/pengguna/profile*') ? 'active' : '' }} parent" href="{{ route('profile.index') }}">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-user"></i>
                    </div>
                    Ubah Profil
                </a>
                @endcan
            </div>
        </div>
    </nav>
</div>
<style>
    .nav-link {
        margin: 0px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
    }
    .nav-link.active {
        background-color: grey;
    }
    .nav-link.active.child {
        background-color: #343a40;
    }
    .nav-link .fa-caret-down,
    .nav-link .fa-caret-right {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        transition: transform 0.3s ease;
    }
    .nav-link.collapsed .fa-caret-down {
        transform: translateY(-50%) rotate(90deg);
    }
    .nav-link.collapsed.active.parent .fa-caret-down {
        transform: translateY(-50%) rotate(0deg);
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const activeCollapse = urlParams.get('collapse');
        if (activeCollapse) {
            const collapseElement = document.getElementById(activeCollapse);
            if (collapseElement) {
                const bsCollapse = new bootstrap.Collapse(collapseElement);
                bsCollapse.show();
            }
        }
        const collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach((collapseElement) => {
            collapseElement.addEventListener('hidden.bs.collapse', function () {
                const navLink = document.querySelector(`[data-bs-target="#${this.id}"]`);
                if (navLink) {
                    navLink.classList.remove('parent');
                    navLink.classList.add('collapsed');
                }
            });
        });
    });
</script>