@extends('layouts.admin')
@section('content')
<div class="container-fluid px-4">
    <div class="row align-items-center">
        <div class="col-sm-6 col-md-12">
            <h1 class="mt-4">{{ $title }}</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @can('admin')
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalChoose">
                <i class="fa-solid fa-clipboard-check"></i>
                Kriteria
            </button>
            @endcan
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-bordered">
                    <thead class="bg-primary text-white align-middle text-center">
                        <tr>
                            <th>No</th>
                            <th>Dibuat Oleh</th>
                            <th>Kriteria</th>
                            <th>Dibuat Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($comparisons->count())
                        @foreach ($comparisons as $comparison)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $comparison->user->name }}</td>
                            <td>
                                @foreach ($comparison->details->unique('criteria_id_second') as $key => $detail)
                                {{ $detail->criteria_name }}
                                @if (!$loop->last)
                                ,
                                @endif
                                @endforeach
                            </td>
                            <td>{{ $comparison->created_at->translatedFormat('l, j F Y H:i:s') }}</td>
                            <td>
                                @can('admin')
                                <a href="{{ route('kombinasi.update', $comparison->id) }}" class="badge bg-warning text-decoration-none">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit Perbandingan Kriteria
                                </a>
                                <form action="{{ route('kombinasi.destroy', $comparison->id) }}" method="POST" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button class="badge bg-danger border-0 btnDelete" data-object="perhitungan dari {{ $comparison->user->name }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                        Hapus Perhitungan
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="text-danger text-center p-4">
                                <h4>Belum Ada Data Untuk Perbandingan Kriteria</h4>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal Choose Criteria -->
    <div class="modal fade" id="modalChoose" tabindex="-1" aria-labelledby="modalChooseLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChooseLabel">Pilih Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('kombinasi.index') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white text-center">
                                <tr>
                                    <th colspan="2">Nama Kriteria</th>
                                    <th>Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($criterias->count())
                                @foreach ($criterias as $criteria)
                                <tr>
                                    <th scope="row" class="text-center">
                                        <input type="checkbox" value="{{ $criteria->id }}" name="criteria_id[]">
                                    </th>
                                    <td>{{ $criteria->nama_kriteria }}</td>
                                    <td>{{ Str::ucfirst(Str::lower($criteria->kategori)) }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td class="text-center text-danger" colspan="3">Tidak Ditemukan Kriteria</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection