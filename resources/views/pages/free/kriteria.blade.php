@extends('layouts.free')
@section('content')
<main>
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
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        {{-- datatable --}}
        <div class="card mb-4">
            <div class="card-body table-responsive">
                {{-- validation error file required --}}
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
        </div>
    </div>
</main>
@endsection