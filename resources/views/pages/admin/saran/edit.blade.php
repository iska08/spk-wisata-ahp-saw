@extends('layouts.admin')
@section('content')
<div class="container-fluid px-4 border-bottom">
    <h1 class="mt-4 h2">{{ $title }}</h1>
</div>
<div class="containter-fluid px-4 mt-3">
    @can('admin')
    <div class="row align-items-center">
        <form class="col-lg-8" method="POST" action="{{ route('sarans.update', $saran->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label for="nama_wisata" class="form-label">Saran Nama Destinasi Wisata</label>
                <input type="text" class="form-control" id="nama_wisata" name="nama_wisata" value="{{ $saran->nama_wisata }}" readonly disabled>
                <input type="hidden" name="nama_wisata" value="{{ $saran->nama_wisata }}">
            </div>
            <div class="mb-3">
                <label for="jenis_id" class="form-label">Jenis Wisata</label>
                <select class="form-select" name="jenis_id" readonly disabled>
                    <option value="" disabled selected>Pilih Jenis Wisata</option>
                    @foreach ($jenises as $jenis)
                        @if (old('jenis_id', $saran->jenis_id) == $jenis->id)
                            <option value="{{ $jenis->id }}" selected>{{ $jenis->jenis_name }}</option>
                        @else
                            <option value="{{ $jenis->id }}">{{ $jenis->jenis_name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('jenis_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="validasi" class="form-label">Validasi</label>
                <select class="form-select" id="validasi" name="validasi" required>
                    <option value="0" {{ $saran->validasi == 0 ? 'selected' : '' }}>Belum Disetujui</option>
                    <option value="1" {{ $saran->validasi == 1 ? 'selected' : '' }}>Tidak Disetujui</option>
                    <option value="2" {{ $saran->validasi == 2 ? 'selected' : '' }}>Disetujui</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Simpan Perubahan</button>
            <a href="/dashboard/sarans" class="btn btn-danger mb-3">Cancel</a>
        </form>
    </div>
    @elseif('user')
    <div class="row align-items-center">
        <form class="col-lg-8" method="POST" action="{{ route('sarans.update', $saran->id) }}"
            enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label for="nama_wisata" class="form-label">Saran Nama Destinasi Wisata</label>
                <input type="text" class="form-control" id="nama_wisata" name="nama_wisata" value="{{ $saran->nama_wisata }}">
            </div>
            <div class="mb-3">
                <label for="jenis_id" class="form-label">Jenis Wisata</label>
                <select class="form-select" name="jenis_id">
                    <option value="" disabled selected>Pilih Jenis Wisata</option>
                    @foreach ($jenises as $jenis)
                        @if (old('jenis_id', $saran->jenis_id) == $jenis->id)
                            <option value="{{ $jenis->id }}" selected>{{ $jenis->jenis_name }}</option>
                        @else
                            <option value="{{ $jenis->id }}">{{ $jenis->jenis_name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('jenis_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan">{{ $saran->keterangan }}</textarea>
            </div>
            <input type="hidden" class="form-control" id="validasi" name="validasi" value="{{ $saran->validasi }}">
            <button type="submit" class="btn btn-primary mb-3">Simpan Perubahan</button>
            <a href="/dashboard/sarans" class="btn btn-danger mb-3">Cancel</a>
        </form>
    </div>
    @endcan
</div>
@endsection