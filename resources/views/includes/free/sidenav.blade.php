<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link {{ Request::is('spk') ? 'active' : '' }} parent" href="{{ route('free.index') }}">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-home"></i>
                    </div>
                    <b>Dashboard</b>
                </a>
                {{-- Dropdown Master Data --}}
                <a href="#" class="nav-link collapsed {{ Request::is('spk/data/*') ? 'active' : '' }} parent" data-bs-toggle="collapse" data-bs-target="#masterDataCollapse" aria-expanded="false">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <b>Master Data</b>
                    <i class="fas fa-caret-down ms-auto"></i>
                </a>
                <div class="collapse {{ Request::is('spk/data/*') ? 'show' : '' }}" id="masterDataCollapse" data-bs-parent="#sidenavAccordion">
                    <a class="nav-link {{ Request::is('spk/data/kriteria*') ? 'active' : '' }} child" href="{{ route('free.kriteria') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Kriteria
                    </a>
                    <a class="nav-link {{ Request::is('spk/data/wisata*') ? 'active' : '' }} child" href="{{ route('free.wisata') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Destinasi Wisata
                    </a>
                    <a class="nav-link {{ Request::is('spk/data/jenis*') ? 'active' : '' }} child" href="{{ route('free.jenis') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Jenis Wisata
                    </a>
                    <a class="nav-link {{ Request::is('spk/data/alternatif*') ? 'active' : '' }} child" href="{{ route('free.alternatif') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Data Alternatif
                    </a>
                </div>
                {{-- Dropdown Master SPK --}}
                <a href="#" class="nav-link collapsed {{ Request::is('spk/perhitungan*') ? 'active' : '' }} parent" data-bs-toggle="collapse" data-bs-target="#masterDataCollapse2" aria-expanded="false">
                    <div class="sb-nav-link-icon col-1">
                        <i class="fas fa-ranking-star"></i>
                    </div>
                    <b>Master SPK</b>
                    <i class="fas fa-caret-down ms-auto"></i>
                </a>
                <div class="collapse {{ Request::is('spk/perhitungan*') ? 'show' : '' }}" id="masterDataCollapse2" data-bs-parent="#sidenavAccordion">
                    <a class="nav-link {{ Request::is('spk/perhitungan*') ? 'active' : '' }} child" href="{{ route('free.perhitungan') }}">
                        <div class="sb-nav-link-icon col-1">
                            <i class="fas"></i>
                        </div>
                        Metode SPK
                    </a>
                </div>
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