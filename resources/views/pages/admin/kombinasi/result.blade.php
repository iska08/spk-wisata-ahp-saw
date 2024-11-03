@extends('layouts.admin')
@section('content')
<div class="container-fluid px-4">
    <div class="row align-items-center">
        <div class="col-sm-6 col-md-12">
            <h1 class="mt-4">{{ $title }}</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kombinasi.awal') }}">Metode SPK</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('kombinasi.detail', $criteria_analysis->id) }}">
                        Detail Perhitungan Kombinasi
                    </a>
                </li>
            </ol>
        </div>
    </div>
    {{-- matrik penjumlahan(prespektif nilai) --}}
    <div class="card mb-4">
        <div class="card-body d-sm-flex align-items-center" style="padding: 20px 20px 2px">
            <h4 class="mb-0 text-gray-800">AHP - Matriks Perbandingan dan Penjumlahan Kolom Kriteria</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="bg-primary align-middle text-center">
                    <tr>
                        <th scope="col" class="text-white"><i>Kriteria</i></th>
                        @foreach ($criteria_analysis->priorityValues as $priorityValue)
                        <th scope="col">
                            {{ $priorityValue->criteria->nama_kriteria }}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @php($startAt = 0)
                    @foreach ($criteria_analysis->priorityValues as $priorityValue)
                    @php($bgYellow = 'bg-warning text-dark')
                    <tr>
                        <th scope="row" class="text-center bg-primary">
                            {{ $priorityValue->criteria->nama_kriteria }}
                        </th>
                        @foreach ($criteria_analysis->priorityValues as $priorityvalue)
                        @if (
                        $criteria_analysis->details[$startAt]->criteria_id_first ===
                        $criteria_analysis->details[$startAt]->criteria_id_second)
                        @php($bgYellow = '')
                        <td class="text-center bg-success text-white">
                            {{ floatval($criteria_analysis->details[$startAt]->comparison_result) }}
                        </td>
                        @else
                        <td class="text-center {{ $bgYellow }}">
                            {{ round(floatval($criteria_analysis->details[$startAt]->comparison_result), 3) }}
                        </td>
                        @endif
                        @php($startAt++)
                        @endforeach
                    </tr>
                    @endforeach
                    <th class="text-center bg-dark text-white">Jumlah</th>
                    @foreach ($totalSums as $total)
                    <td class="text-center bg-dark text-white">
                        {{ round($total['totalSum'], 3) }}
                    </td>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Normalisasi dan prioritas --}}
    <div class="card mb-4">
        <div class="card-body d-sm-flex align-items-center" style="padding: 20px 20px 2px">
            <h4 class="mb-0 text-gray-800">AHP - Matriks Normalisasi Kriteria dan Eigen Vector (EV)</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary align-middle text-center">
                    <tr>
                        <th scope="col" class="bg-primary text-white"><i>Kriteria</i></th>
                        @foreach ($criteria_analysis->priorityValues as $priorityValue)
                        <th scope="col" class="bg-primary">
                            {{ $priorityValue->criteria->nama_kriteria }}
                        </th>
                        @endforeach
                        <th scope="col" class="text-center bg-success text-white">Jumlah</th>
                        <th scope="col" class="text-center bg-dark text-white">Eigen Vector (EV)</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @php($startAt = 0)
                    @php($rowTotals = [])
                    @foreach ($criteria_analysis->priorityValues as $priorityValue)
                    @php($rowTotal = 0)
                    @php($bgYellow = 'bg-warning text-dark')
                    <tr>
                        <th scope="row" class="text-center bg-primary">
                            {{ $priorityValue->criteria->nama_kriteria }}
                        </th>
                        @foreach ($criteria_analysis->priorityValues as $key => $priorityvalue)
                        <td class="text-center">
                            @php($res = floatval($criteria_analysis->details[$startAt]->comparison_result) /
                            $totalSums[$key]['totalSum'])
                            {{ round($res, 3) }}
                            @php($rowTotal += Str::substr($res, 0, 11))
                        </td>
                        @php($startAt++)
                        @endforeach
                        @php(array_push($rowTotals, $rowTotal))
                        <td class="text-center bg-success text-white">
                            {{ round($rowTotal, 3) }}
                        </td>
                        <td class="text-center bg-dark text-white">
                            {{ round($priorityValue->value, 3) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Matriks perkalian --}}
    <div class="card mb-4">
        <div class="card-body d-sm-flex align-items-center" style="padding: 20px 20px 2px">
            <h4 class="mb-0 text-gray-800">AHP - Matriks Perkalian Setiap Elemen dengan Eigen Vector (EV)</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary align-middle text-center">
                    <tr>
                        <th scope="col" class="bg-primary text-white"><i>Kriteria</i></th>
                        @foreach ($criteria_analysis->priorityValues as $priorityValue)
                        <th scope="col" class="bg-primary">{{ $priorityValue->criteria->nama_kriteria }}</th>
                        @endforeach
                        <th scope="col" class="bg-dark text-white">Jumlah Baris</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @php($startAt = 0)
                    @php($rowTotals = [])
                    @foreach ($criteria_analysis->priorityValues as $priorityValue)
                    @php($rowTotal = 0)
                    <tr>
                        <th scope="row" class="text-center bg-primary">
                            {{ $priorityValue->criteria->nama_kriteria }}
                        </th>
                        @foreach ($criteria_analysis->priorityValues as $key => $innerpriorityvalue)
                        <td class="text-center">
                            @php($res = floatval($criteria_analysis->details[$startAt]->comparison_result) *
                            $innerpriorityvalue->value)
                            {{-- hasil perkalian --}}
                            {{ round($res, 3) }}
                            {{-- row total --}}
                            @php($rowTotal += Str::substr($res, 0, 11))
                        </td>
                        @php($startAt++)
                        @endforeach
                        @php(array_push($rowTotals, $rowTotal))
                        <td class="text-center bg-dark text-white">
                            {{-- {{ $rowTotal }} --}}
                            {{ round($rowTotal, 3) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- λ --}}
    <div class="card mb-4">
        <div class="card-body d-sm-flex align-items-center" style="padding: 20px 20px 2px">
            <h4 class="mb-0 text-gray-800">AHP - Menentukan λmaks dan Rasio Konsistensi</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="bg-primary align-middle text-center">
                    <tr>
                        <th scope="col">Kriteria</th>
                        <th scope="col">Jumlah Baris</th>
                        <th scope="col">Eigen Vector (EV)</th>
                        <th scope="col">λ</th>
                    </tr>
                </thead>
                <tbody>
                    @php($lambdaMax = null)
                    @php($lambdaResult = [])
                    @foreach ($rowTotals as $key => $total)
                    <tr>
                        <td scope="row" class="text-center">
                            {{ $criteria_analysis->priorityValues[$key]->criteria->nama_kriteria }}
                        </td>
                        <td class="text-center">
                            {{ round($total, 3) }}
                        </td>
                        <td class="text-center">
                            {{ round($criteria_analysis->priorityValues[$key]->value, 3) }}
                        </td>
                        <td class="text-center">
                            @php($lambda = $total / $criteria_analysis->priorityValues[$key]->value)
                            @php($res = substr($lambda, 0, 11))
                            @php(array_push($lambdaResult, $res))
                            {{ round($res, 3) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="text-center fw-bold bg-dark text-white" colspan="3">λmaks</td>
                        <td class="text-center fw-bold bg-dark text-white">
                            @php($lambdaMax = array_sum($lambdaResult) / count($lambdaResult))
                            {{ round($lambdaMax, 3) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-lg-flex justify-content-center">
                <div class="col-12 col-lg-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-center" scope="row">Banyak Kriteria (n)</th>
                                <td class="text-center">{{ $criteria_analysis->priorityValues->count() }}</td>
                            </tr>
                            <tr>
                                <th class="text-center" scope="row">λmaks</th>
                                <td class="text-center">{{ round($lambdaMax, 3) }}</td>
                            </tr>
                            <tr>
                                <th class="text-center" scope="row">Consistency Index (CI)</th>
                                <td class="text-center">
                                    @php($CI = ($lambdaMax - count($lambdaResult)) / (count($lambdaResult) - 1))
                                    {{ round($CI, 3) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center" scope="row">Random Index (RI)</th>
                                <td class="text-center">
                                    @php($RC = $ruleRC[$criteria_analysis->priorityValues->count()])
                                    {{ $RC }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center" scope="row">Consistency Ratio (CR)</th>
                                @php($CR = $RC != 0.0 ? $CI / $RC : 0.0)
                                @php($txtClass = 'text-danger fw-bold')
                                @if ($CR <= 0.1) @php($txtClass='text-success fw-bold' ) @endif
                                <td class="{{ $txtClass }} text-center">
                                    <span id="cr-value">{{ round($CR, 3) }}</span>
                                    (Nilai Konsisten)
                                </td>
                            </tr>
                            <tr>
                                @if ($CR > 0.1)
                                <td class="text-center text-danger" colspan="2">
                                    Nilai Rasio Konsistensi Melebihi <b>0.1</b> <br>
                                    Masukkan Kembali Nilai Perbandingan Kriteria
                                    <a href="{{ route('kombinasi.update', $criteria_analysis->id) }}" class="btn btn-danger mt-2">Masukkan Kembali Nilai Perbandingan</a>
                                </td>
                                @elseif(!$isAbleToRank)
                                <td class="text-center text-danger" colspan="2">
                                    Admin Belum Memasukkan Alternatif Apapun <br>
                                    Harap Menunggu Admin Untuk Menginputkan Alternatif Sebelum Melihat Peringkat
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Normalisasi --}}
    <div class="card mb-4">
        <div class="card-body d-sm-flex align-items-center" style="padding: 20px 20px 2px">
            <h4 class="mb-0 text-gray-800">SAW - Normalisasi Tabel Alternatif</h4>
        </div>
        <div class="card-body table-responsive" style="padding: 20px 20px 2px">
            <table class="table table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td scope="col" class="fw-bold text-center" style="width:11%">Kategori</td>
                        @foreach ($dividers as $divider)
                        <td class="text-center" scope="col">{{ $divider['kategori'] }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td scope="col" class="fw-bold text-center" style="width:11%">Nilai Pembagi</td>
                        @foreach ($dividers as $divider)
                        <td class="text-center" scope="col">{{ $divider['divider_value'] }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td scope="col" class="fw-bold text-center" style="width:11%">EV/Bobot</td>
                        @foreach ($criteriaAnalysis->priorityValues as $key => $innerpriorityvalue)
                        <td class="text-center">
                            {{ round($innerpriorityvalue->value, 3) }}
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-body table-responsive">
            <table id="datatablesSimple" class="table table-bordered">
                <thead class="bg-primary align-middle text-center">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col" class="text-center">Nama Alternatif</th>
                        <th scope="col" class="text-center">Jenis Wisata</th>
                        @foreach ($dividers as $divider)
                        <th scope="col">{{ $divider['nama_kriteria'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @if (!empty($normalizations))
                    @foreach ($normalizations as $normalisasi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">
                            {{ Str::ucfirst($normalisasi['nama_wisata']) }}
                        </td>
                        <td class="text-center">
                            {{ $normalisasi['jenis_name'] }}
                        </td>
                        @foreach ($dividers as $key => $value)
                        @if (isset($normalisasi['results'][$key]))
                        <td class="text-center">
                            {{ round($normalisasi['results'][$key], 3) }}
                        </td>
                        @else
                        <td class="text-center">
                            Empty
                        </td>
                        @endif
                        @endforeach
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- Ranking --}}
    <div class="card mb-4">
        <div class="card-body d-sm-flex align-items-center" style="padding: 20px 20px 2px">
            <h4 class="mb-0 text-gray-800">SAW - Ranking</h4>
        </div>
        <div class="card-body table-responsive">
            <table id="datatablesSimple2" class="table table-bordered">
                <thead class="bg-primary align-middle text-center">
                    <tr>
                        <th scope="col">Nama Alternatif</th>
                        <th scope="col">Jenis Wisata</th>
                        @foreach ($dividers as $divider)
                        <th scope="col">
                            Hitung {{ $divider['nama_kriteria'] }}
                        </th>
                        @endforeach
                        <th scope="col" class="text-center">Jumlah</th>
                        <th scope="col" class="text-center">Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($ranks))
                    @php($rankResult = [])
                    @php($hasilKali = [])
                    @foreach ($ranks as $rank)
                    <tr>
                        <td>
                            {{ $rank['nama_wisata'] }}
                        </td>
                        <td>
                            {{ $rank['jenis_name'] }}
                        </td>
                        @foreach ($criteriaAnalysis->priorityValues as $key => $innerpriorityvalue)
                        @php($hasilNormalisasi = isset($rank['results'][$key]) ? $rank['results'][$key] : 0)
                        <td class="text-center">
                            @php($kali = $innerpriorityvalue->value * $hasilNormalisasi)
                            @php($res = substr($kali, 0, 11))
                            @php(array_push($hasilKali, $res))
                            {{ round($res, 3) }}
                        </td>
                        @endforeach
                        <td class="text-center">
                            {{ round($rank['rank_result'], 3) }}
                        </td>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@php(session(["cr_value_{$criteria_analysis->id}" => round($CR, 3)]))
@endsection