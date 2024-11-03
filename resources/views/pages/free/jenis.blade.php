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
            {{-- datatable --}}
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="datatablesSimple" class="table table-bordered">
                        <thead class="bg-primary align-middle text-center text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Jenis Wisata</th>
                                <th>Keterangan</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($jenises->count())
                                @foreach ($jenises as $jenis)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            {{ Str::ucfirst($jenis->jenis_name) }}
                                        </td>
                                        <td class="text-center">
                                            {{ $jenis->keterangan }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('free.jenisSlug', $jenis->slug) }}" class="badge bg-primary">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-danger text-center p-4">
                                        <h4>Belum ada data Jenis Wisata</h4>
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