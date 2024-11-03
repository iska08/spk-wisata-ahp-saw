@extends('layouts.free')
@section('content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Rekomendasi Destinasi Wisata</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Rekomendasi Destinasi Wisata</li>
        </ol>
    </div>
    <div class="container-fluid px-4">
        <center><img src="{{ url('frontend/images/Malang Raya.jpg') }}" style="height: 4cm; width: 6cm"/></center><br>
        <div class="container d-flex justify-content-center align-items-center text-center position-relative">
            <h4>
                <p style="font-family: 'Times New Roman', Times, serif; font-size: 45px">SELAMAT DATANG DI</p>
                <i style="font-family: 'Courier New', Courier, monospace">Sistem Pendukung Keputusan Rekomendasi Destinasi Wisata di Malang Raya</i>
            </h4>
        </div>
        <br>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="datatablesSimple" class="table table-bordered">
                    <thead class="bg-primary text-white align-middle text-center">
                        <tr>
                            <th>No</th>
                            <th>Kriteria yang Menjadi Acuan</th>
                            <th>Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($comparisons->count())
                        @foreach ($comparisons as $comparison)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                @foreach ($comparison->details->unique('criteria_id_second') as $key => $detail)
                                {{ $detail->criteria_name }}
                                @if (!$loop->last)
                                ,
                                @endif
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('free.rekomendasi', $comparison->id) }}" class="badge bg-success text-decoration-none">
                                    <i class="fa-solid fa-eye"></i>
                                    Lihat Rekomendasi Destinasi Wisata
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="text-danger text-center p-4">
                                <h4>Belum Ada Data Untuk Perbandingan Kriteria</h4>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection