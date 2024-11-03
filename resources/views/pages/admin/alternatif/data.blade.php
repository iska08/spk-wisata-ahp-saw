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
        {{-- datatable --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-sm-flex align-items-center">
                    @can('admin')
                    <button type="button" class="btn btn-primary mb-3 me-auto" data-bs-toggle="modal" data-bs-target="#addAlternativeModal">
                        <i class="fas fa-plus me-1"></i>
                        Alternatif
                    </button>
                    @endcan
                </div>
                {{-- error handle --}}
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                {{-- file request --}}
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="d-sm-flex align-items-center justify-content-between mb-3">
                    <div class="d-sm-flex align-items-center mb-3">
                        <select class="form-select me-3" id="perPage" name="perPage" onchange="submitForm()">
                            @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" {{ $option == $perPage ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                            @endforeach
                        </select>
                        <label class="form-label col-lg-6 col-sm-6 col-md-6" for="perPage">entries per page</label>
                    </div>
                    <form action="{{ route('alternatif.index') }}" method="GET" class="ms-auto">
                        <div class="input-group mb-3">
                            <input type="text" name="search" id="myInput" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-primary align-middle text-center text-white">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Alternatif</th>
                                <th rowspan="2">Jenis Wisata</th>
                                <th colspan="{{ $criterias->count() }}">Kriteria</th>
                                @can('admin')
                                <th rowspan="2">Aksi</th>
                                @endcan
                            </tr>
                            <tr>
                                @if ($criterias->count())
                                @foreach ($criterias as $criteria)
                                <th>{{ $criteria->nama_kriteria }}</th>
                                @endforeach
                                @else
                                <th>Data Kriteria Tidak Ditemukan</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="align-middle" id="myTable">
                            @if ($alternatives->count())
                            @foreach ($alternatives as $alternative)
                            <tr>
                                <td scope="row" class="text-center">
                                    {{ ($alternatives->currentpage() - 1) * $alternatives->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="text-center">
                                    {{ Str::ucfirst($alternative->nama_wisata) }}
                                </td>
                                <td class="text-center">
                                    {{ $alternative->jenis->jenis_name }}
                                </td>
                                @foreach ($criterias as $key => $criteria)
                                @if (isset($alternative->alternatives[$key]))
                                <td class="text-center">
                                    {{ floatval($alternative->alternatives[$key]->alternative_value) }}
                                </td>
                                @else
                                <td class="text-center">
                                    Empty
                                </td>
                                @endif
                                @endforeach
                                @can('admin')
                                <td class="text-center">
                                    <a href="{{ route('alternatif.edit', $alternative->id) }}" class="badge bg-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('alternatif.destroy', $alternative->id) }}" method="POST" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button class="badge bg-danger border-0 btnDelete" data-object="Alternatif {{ $alternative->nama_wisata }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="{{ 5 + $criterias->count() }}" class="text-center text-danger p-4" style="font-size: 24px">
                                    Belum Ada Data
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $alternatives->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    <!-- Add Alternative -->
    <div class="modal fade" id="addAlternativeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addAlternativeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlternativeModalLabel">Tambah Alternatif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('alternatif.index') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="my-2">
                            <label for="wisata_id" class="form-label">Daftar Destinasi Wisata</label>
                            <select class="form-select @error('wisata_id') is-invalid @enderror" id="wisata_id" name="wisata_id" required>
                                <option disabled selected value="">-- Pilih Destinasi Wisata --</option>
                                @if ($wisata_list->count())
                                @foreach ($wisata_list as $jenis => $wisatas)
                                <optgroup label="Destinasi {{ $jenis }}: {{ $wisatas->count() }}">
                                    @foreach ($wisatas as $wisata)
                                    <option value="{{ $wisata->id }} {{ $wisata->jenisId }}">
                                        {{ $wisata->nama_wisata }} - {{ $wisata->jenis->jenis_name }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                                @else
                                <option disabled selected>-- TIDAK ADA DATA --</option>
                                @endif
                            </select>
                            @error('wisata_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @if ($criterias->count())
                        @foreach ($criterias as $key => $criteria)
                        <input type="hidden" name="criteria_id[]" value="{{ $criteria->id }}">
                        <div class="my-2">
                            <label for="{{ str_replace(' ', '', $criteria->nama_kriteria) }}" class="form-label">
                                Nilai <b>{{ $criteria->nama_kriteria }}</b>
                            </label>
                            <select class="form-select @error('alternative_value') is-invalid @enderror" id="{{ str_replace(' ', '', $criteria->nama_kriteria) }}" name="alternative_value[]" required>
                                <option disabled selected value="">-- Pilih Sub Kriteria --</option>
                                <option value="1">(Skala 1) - {{ $criteria->skala1 }}</option>
                                <option value="2">(Skala 2) - {{ $criteria->skala2 }}</option>
                                <option value="3">(Skala 3) - {{ $criteria->skala3 }}</option>
                                <option value="4">(Skala 4) - {{ $criteria->skala4 }}</option>
                                <option value="5">(Skala 5) - {{ $criteria->skala5 }}</option>
                            </select>
                            @error('alternative_value')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="{{ $criterias->count() ? 'submit' : 'button' }}" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function submitForm() {
            var perPageSelect = document.getElementById('perPage');
            var perPageValue = perPageSelect.value;
            var currentPage = {{ $alternatives->currentPage() }};
            var url = new URL(window.location.href);
            var params = new URLSearchParams(url.search);
            params.set('perPage', perPageValue);
            if (!params.has('page')) {
                params.set('page', currentPage);
            }
            url.search = params.toString();
            window.location.href = url.toString();
        }
    </script>
@endsection