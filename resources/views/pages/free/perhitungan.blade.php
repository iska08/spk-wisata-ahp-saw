@extends('layouts.free')
@section('content')
<div class="container-fluid px-4">
    <div class="row align-items-center">
        <div class="col-sm-6 col-md-12">
            <h1 class="mt-4">{{ $title }}</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('free.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>
    <div class="card">
        <div class="card-body table-responsive">
            <table id="datatablesSimple" class="table table-bordered">
                <thead class="bg-primary text-white align-middle text-center">
                    <tr>
                        <th>No</th>
                        <th>Kriteria</th>
                        <th>Jenis Metode</th>
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
                            <a href="{{ route('perhitungan.kombinasi', $comparison->id) }}" class="badge bg-success text-decoration-none">
                                <i class="fa-solid fa-eye"></i>
                                Perhitungan Kombinasi
                            </a>
                            <a href="{{ route('perhitungan.ahp', $comparison->id) }}" class="badge bg-success text-decoration-none">
                                <i class="fa-solid fa-eye"></i>
                                Perhitungan AHP
                            </a>
                            <a href="{{ route('perhitungan.saw', $comparison->id) }}" class="badge bg-success text-decoration-none">
                                <i class="fa-solid fa-eye"></i>
                                Perhitungan SAW
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="text-danger text-center p-4">
                            <h4>Belum Ada Data Untuk Perbandingan Kriteria</h4>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection