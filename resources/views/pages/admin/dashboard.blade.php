@extends('layouts.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
    <div class="container-fluid px-4">
        <?php
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($userAgent, 'Mobile') !== false || strpos($userAgent, 'Android') !== false || strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
            ?>
            <center><img src="{{ url('frontend/images/Malang Raya.jpg') }}" style="height: 4cm; width: 6cm"/></center>
            <br>
            <h4 class="text-center">
                <p style="font-family: 'Times New Roman', Times, serif; font-size: 30px">SELAMAT DATANG DI</p>
                <i style="font-family: 'Courier New', Courier, monospace">Sistem Pendukung Keputusan Pemilihan Destinasi Wisata di Malang Raya</i>
            </h4>
            @can('admin')
            <hr style="border-width: 2px;">
            <!-- Content Row -->
            <div class="row d-flex justify-content-center align-items-center position-relative">
                <!-- Destinasi Wisata -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <a style="text-decoration:none; color: #212529;" href="{{ route('wisata.index') }}">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Destinasi Wisata
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $wisata }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Criteria -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <a href="{{ route('kriteria.index') }}" style="text-decoration:none; color: #212529;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Kriteria
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criterias }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Jenis Wisata -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <a href="{{ route('jenis.index') }}" style="text-decoration:none; color: #212529;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Jenis Wisata
                                        </div>
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $jenis }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-hiking fa-3x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Data User -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <a href="{{ route('users.index') }}" style="text-decoration:none; color: #212529;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pengguna
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-gear fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @elseif('user')
            <hr style="border-width: 2px;">
            <div class="slide-container">
                <div class="slide-content">
                    @foreach ($wisatas as $wisata)
                    <div class="slide-item">
                        <div class="d-flex justify-content position-relative" style="max-width: 80%">
                            @if ($wisata->foto == "" || $wisata->foto == "-")
                            <img src="{{ url('frontend/images/noimage.png') }}" alt="Gambar" style="width: 4cm; height: 3cm">
                            @else
                            <img src="{{ asset('storage/' . $wisata->foto) }}" alt="Gambar" style="width: 4cm; height: 3cm">
                            @endif
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <p style="font-family: 'Times New Roman', Times, serif; font-size: 24px; width:3.5cm">
                                {{ $wisata->nama_wisata }}
                            </p>
                        </div>
                        <br>
                        <h4 style="max-width: 80%">
                            <p class="justify-content-center" style="font-family: 'Times New Roman', Times, serif; font-size: 12px; width: 8cm">
                                <strong>Keterangan:</strong>
                                <br>
                                {{ $wisata->keterangan }}
                            </p>
                            <p style="font-family: 'Times New Roman', Times, serif; font-size: 12px">
                                <strong>Website Resmi:</strong> 
                                @if($wisata->situs == "" || $wisata->situs == "-")
                                -
                                @else
                                <a href="{{ $wisata->situs }}" target="_blank">Klik di Sini</a>
                                @endif
                            </p>
                            <p class="justify-content-center" style="font-family: 'Times New Roman', Times, serif; font-size: 12px; width: 8cm">
                                <strong>Fasilitas:</strong>
                                <br>
                                {{ $wisata->fasilitas}}
                            </p>
                        </h4>
                    </div>
                    @endforeach
                </div>
            </div>
            @endcan
        <?php
        }else{
            ?>
            <div class="container d-flex justify-content-center align-items-center text-center position-relative">
                <img src="{{ url('frontend/images/Malang Raya.jpg') }}" style="height: 4cm; width: 6cm"/>&nbsp;&nbsp;
                <h4>
                    <p style="font-family: 'Times New Roman', Times, serif; font-size: 45px">SELAMAT DATANG DI</p>
                    <i style="font-family: 'Courier New', Courier, monospace">Sistem Pendukung Keputusan Pemilihan Destinasi Wisata di Malang Raya</i>
                </h4>
            </div>
            @can('admin')
            <hr style="border-width: 2px;">
            <!-- Content Row -->
            <div class="row d-flex justify-content-center align-items-center position-relative">
                <!-- Destinasi Wisata -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <a style="text-decoration:none; color: #212529;" href="{{ route('wisata.index') }}">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Destinasi Wisata
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $wisata }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Criteria -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <a href="{{ route('kriteria.index') }}" style="text-decoration:none; color: #212529;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Kriteria
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criterias }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Jenis Wisata -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <a href="{{ route('jenis.index') }}" style="text-decoration:none; color: #212529;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Jenis Wisata
                                        </div>
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $jenis }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-hiking fa-3x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Data User -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <a href="{{ route('users.index') }}" style="text-decoration:none; color: #212529;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pengguna
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-gear fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @elseif('user')
            <hr style="border-width: 2px;">
            <div class="slide-container">
                <div class="slide-content">
                    @foreach ($wisatas as $wisata)
                    <div class="slide-item">
                        <div class="container d-flex justify-content position-relative">
                            @if ($wisata->foto == "" || $wisata->foto == "-")
                            <img src="{{ url('frontend/images/noimage.png') }}" alt="Gambar" style="height: 4cm; width: 6cm">
                            @else
                            <img src="{{ asset('storage/' . $wisata->foto) }}" alt="Gambar" style="height: 4cm; width: 6cm">
                            @endif
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <h4 style="max-width: 30%; min-width:30%">
                                <p style="font-family: 'Times New Roman', Times, serif; font-size: 24">{{ $wisata->nama_wisata }}</p>
                                <p style="font-family: 'Times New Roman', Times, serif; font-size: 12px">{{ $wisata->keterangan }}</p><br>
                                <p style="font-family: 'Times New Roman', Times, serif; font-size: 12px">
                                    Website Resmi: 
                                    @if($wisata->situs == "" || $wisata->situs == "-")
                                    -
                                    @else
                                    <a href="{{ $wisata->situs }}" target="_blank">Klik di Sini</a>
                                    @endif
                                </p>
                            </h4>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <h4 style="max-width: 30%; min-width:30%">
                                <p style="font-family: 'Times New Roman', Times, serif; font-size: 24">Fasilitas</p>
                                <p style="font-family: 'Times New Roman', Times, serif; font-size: 12px">{{ $wisata->fasilitas}}</p>
                            </h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endcan
            <?php
        }?>
    </div>
</main>
@endsection