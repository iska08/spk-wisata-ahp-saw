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
    <div class="card mb-4">
        <div class="card-body d-sm-flex align-items-center" style="padding: 0px 0px 0px 0px">
            <div class="card-body table-responsive">
                Catatan:
                @foreach ($dividers as $divider)
                <ul>
                    <li><b>{{ $divider['nama_kriteria'] }}:</b> {{ $divider['keterangan'] }}</li>
                </ul>
                @endforeach
            </div>
        </div>
        <div class="card-body table-responsive" style="padding: 0px 20px 20px 20px">
            <table id="datatablesSimple" class="table table-bordered">
                <thead class="bg-primary align-middle text-center">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Destinasi Wisata</th>
                        <th class="text-center">Foto</th>
                        <th class="text-center">Jenis Wisata</th>
                        <th class="text-center">Link Google Maps</th>
                        <th class="text-center">Fasilitas</th>
                        <th class="text-center">Biaya</th>
                        <th class="text-center">Situs Resmi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($ranks))
                    @php($rankResult = [])
                    @php($hasilKali = [])
                    @foreach ($ranks as $rank)
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $rank['nama_wisata'] }}
                        </td>
                        <td>
                            @if($rank['foto'] == "" || $rank['foto'] == "-")
                                <img src="{{ url('frontend/images/noimage.png') }}" alt="Gambar" style="width: 4cm; height: 3cm">
                            @else
                                <img src="{{ asset('storage/' . $rank['foto']) }}" alt="Gambar" style="width: 4cm; height: 3cm">
                            @endif
                        </td>
                        <td>
                            {{ $rank['jenis_name'] }}
                        </td>
                        <td>
                            @if($rank['lokasi_maps'] == "" || $rank['lokasi_maps'] == "-")
                                -
                            @else
                                <a href="{{ $rank['lokasi_maps'] }}" target="_blank">Klik di Sini</a>
                            @endif
                        </td>
                        <td>
                            @if($rank['fasilitas'] == "" || $rank['fasilitas'] == "-")
                                -
                            @else
                                {{ $rank['fasilitas'] }}
                            @endif
                        </td>
                        <td>
                            @if($rank['biaya'] == "")
                                -
                            @else
                                Rp {{ number_format($rank['biaya'], 0, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            @if($rank['situs'] == "" || $rank['situs'] == "-")
                                -
                            @else
                                <a href="{{ $rank['situs'] }}" target="_blank">Klik di Sini</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection