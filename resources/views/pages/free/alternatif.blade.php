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
        {{-- datatable --}}
        <div class="card mb-4">
            <div class="card-body">
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
                    <form action="{{ route('free.alternatif') }}" method="GET" class="ms-auto float-end">
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
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="{{ 4 + $criterias->count() }}" class="text-center text-danger p-4" style="font-size: 24px">
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