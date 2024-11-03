@extends('layouts.admin')
@section('content')
<main>
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
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        {{-- datatable --}}
        <div class="card mb-4">
            <div class="card-body">
                @can('admin')
                <a href="{{ route('kriteria.create') }}" type="button" class="btn btn-primary mb-3">
                    <i class="fas fa-plus me-1"></i>
                    Kriteria
                </a>
                @endcan
                {{-- validation error file required --}}
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @can('admin')
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
                    <form action="{{ route('kriteria.index') }}" method="GET" class="ms-auto">
                        <div class="input-group mb-3">
                            <input type="text" name="search" id="myInput" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-bordered">
                        <thead class="bg-primary align-middle text-center text-white">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Kriteria</th>
                                <th rowspan="2">Kategori</th>
                                <th rowspan="2">Keterangan</th>
                                <th colspan="5">Sub Kriteria</th>
                                @can('admin')
                                <th rowspan="2">Aksi</th>
                                @endcan
                            </tr>
                            <tr>
                                <th>Skala 1</th>
                                <th>Skala 2</th>
                                <th>Skala 3</th>
                                <th>Skala 4</th>
                                <th>Skala 5</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($criterias->count())
                            @foreach ($criterias as $criteria)
                            <tr>
                                <td class="text-center bg-primary text-white">{{ $loop->iteration }}</td>
                                <td class="text-center bg-warning">{{ $criteria->nama_kriteria }}</td>
                                <td class="text-center bg-warning">{{ Str::ucfirst(Str::lower($criteria->kategori)) }}</td>
                                <td class="text-center bg-warning">{{ $criteria->keterangan }}</td>
                                <td class="text-center">{{ $criteria->skala1 }}</td>
                                <td class="text-center">{{ $criteria->skala2 }}</td>
                                <td class="text-center">{{ $criteria->skala3 }}</td>
                                <td class="text-center">{{ $criteria->skala4 }}</td>
                                <td class="text-center">{{ $criteria->skala5 }}</td>
                                @can('admin')
                                <td>
                                    <a href="{{ route('kriteria.edit', $criteria->id) }}" class="badge bg-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('kriteria.destroy', $criteria->id) }}" method="POST" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button class="badge bg-danger border-0 btnDelete" data-object="Kriteria {{ $criteria->nama_kriteria }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="10" class="text-danger text-center p-4">
                                    <h4>Kriteria Belum Dibuat</h4>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $criterias->appends(request()->query())->links() }}
                @elseif('user')
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-bordered">
                        <thead class="bg-primary align-middle text-center text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Kriteria</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($criterias->count())
                            @foreach ($criterias as $criteria)
                            <tr>
                                <td class="text-center bg-primary text-white">{{ $loop->iteration }}</td>
                                <td class="text-center bg-warning">{{ $criteria->nama_kriteria }}</td>
                                <td class="text-center bg-warning">{{ $criteria->keterangan }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3" class="text-danger text-center p-4">
                                    <h4>Kriteria Belum Dibuat</h4>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @endcan
            </div>
        </div>
        @can('admin')
        <div class="card mb-4">
            <div class="card-body">
                <p>Kriteria yang telah ditentukan dibagi menjadi dua kategori, yaitu:</p>
                <ul>
                    <li><b>Benefit (Keuntungan):</b> Semakin tinggi nilai keuntungannya maka semakin tinggi peluang untuk dipilih.</li>
                    <li><b>Cost (Biaya):</b> Semakin tinggi nilai cost maka semakin rendah peluang untuk dipilih.</li>
                </ul>
            </div>
        </div>
        @endcan
    </div>
</main>
<script>
    function submitForm() {
        var perPageSelect = document.getElementById('perPage');
        var perPageValue = perPageSelect.value;
        var currentPage = {{ $criterias->currentPage() }};
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