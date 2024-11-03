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
                @elseif('user')
                <a href="{{ route('sarans.create') }}" type="button" class="btn btn-primary mb-3">
                    <i class="fas fa-plus me-1"></i>
                    Saran Destinasi Wisata
                </a>
                @endcan
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-bordered">
                        <thead class="bg-primary align-middle text-center text-white">
                            <tr>
                                <th>No</th>
                                @can('admin')
                                <th>Nama User</th>
                                @endcan
                                <th>Saran Nama Destinasi Wisata</th>
                                <th>Jenis Wisata</th>
                                <th>Keterangan</th>
                                <th>Validasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($sarans->count())
                            @foreach ($sarans as $saran)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                @can('admin')
                                <td class="text-center">{{ $saran->user->name }}</td>
                                <td class="text-center">{{ $saran->nama_wisata }}</td>
                                <td class="text-center">
                                    {{ $saran->jenis->jenis_name ?? 'Tidak Punya Jenis Wisata' }}
                                </td>
                                <td class="text-center">{{ $saran->keterangan }}
                                </td>
                                @if($saran->validasi == 0)
                                <td>
                                    <span class="badge bg-warning">Belum Disetujui</span>
                                </td>
                                @elseif($saran->validasi == 1)
                                <td>
                                    <span class="badge bg-danger">Data Telah Diproses dan Tidak Disetujui</span>
                                </td>
                                @elseif($saran->validasi == 2)
                                <td>
                                    <span class="badge bg-success">Data Telah Diproses dan Disetujui</span>
                                </td>
                                @endif
                                <td>
                                    @if($saran->validasi == 0)
                                    <form action="{{ route('sarans.approve', $saran->id) }}" method="POST" class="d-inline">
                                        @method('PUT')
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        {{-- <button type="submit" class="btn btn-sm btn-success">Terima</button> --}}
                                        <button type="submit" class="btn btn-sm" style="background-color: #3498db">Terima</button>
                                    </form>
                                    <form action="{{ route('sarans.approve', $saran->id) }}" method="POST" class="d-inline">
                                        @method('PUT')
                                        @csrf
                                        <input type="hidden" name="action" value="disapprove">
                                        {{-- <button type="submit" class="btn btn-sm btn-danger">Tolak</button> --}}
                                        <button type="submit" class="btn btn-sm" style="background-color: #e67e22">Tolak</button>
                                    </form>
                                    @else
                                    -
                                    @endif
                                </td>
                                @elseif('user')
                                <td class="text-center">{{ $saran->nama_wisata }}</td>
                                <td class="text-center">
                                    {{ $saran->jenis->jenis_name ?? 'Tidak Punya Jenis Wisata' }}
                                </td>
                                <td class="text-center">{{ $saran->keterangan }}
                                </td>
                                @if ($saran->validasi == 0)
                                <td>
                                    <span class="badge bg-warning">Belum Disetujui</span>
                                </td>
                                @elseif($saran->validasi == 1)
                                <td>
                                    <span class="badge bg-danger">Data Telah Diproses dan Tidak Disetujui</span>
                                </td>
                                @elseif($saran->validasi == 2)
                                <td>
                                    <span class="badge bg-success">Data Telah Diproses dan Disetujui</span>
                                </td>
                                @endif
                                <td>
                                    @if($saran->validasi == 0)
                                    <a href="{{ route('sarans.edit', $saran->id) }}" class="badge bg-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('sarans.destroy', $saran->id) }}" method="POST" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button class="badge bg-danger border-0 btnDelete" data-object="Saran {{ $saran->nama_wisata }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                    @else
                                    -
                                    @endif
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                @can('admin')
                                <td colspan="7" class="text-danger text-center p-4">
                                    <h4>Belum Ada Saran Destinasi Wisata yang Dibuat</h4>
                                </td>
                                @elseif('user')
                                <td colspan="6" class="text-danger text-center p-4">
                                    <h4>Belum Ada Saran Destinasi Wisata yang Dibuat</h4>
                                </td>
                                @endcan
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection