@extends('layouts.admin')
@section('content')
<div class="container-fluid px-4 border-bottom">
    <h1 class="mt-4 h2">{{ $title }}</h1>
</div>
<div class="container-fluid px-4 mt-3">
    <div class="row align-items-center">
        <form class="col-lg-8" method="POST" action="{{ route('sarans.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_wisata" class="form-label">Saran Nama Destinasi Wisata</label>
                <input type="text" class="form-control @error('nama_wisata') is-invalid @enderror" id="nama_wisata" name="nama_wisata" value="{{ old('nama_wisata') }}" autofocus required placeholder="Masukkan Nama Destinasi Wisata">
                @error('nama_wisata')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="jenis_id" class="form-label">Jenis Wisata</label>
                <select class="form-select @error('jenis_id') is-invalid @enderror" id="jenis_id" name="jenis_id" required>
                    <option value="" disabled selected>Pilih Jenis Wisata</option>
                    @foreach ($jenises as $jenis)
                        @if (old('jenis_id') == $jenis->id)
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
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" autofocus required placeholder="Masukkan Keterangan">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="lokasi_maps" value="-">
            <input type="hidden" name="link_foto" value="-">
            <input type="hidden" name="fasilitas" value="-">
            <input type="hidden" name="biaya" value="0">
            <input type="hidden" name="situs" value="-">
            <input type="hidden" name="validasi" value="0">
            <input type="hidden" name="tampil" value="0">
            <button type="submit" class="btn btn-primary mb-3">Simpan</button>
            <a href="{{ route('sarans.index') }}" class="btn btn-danger mb-3">Cancel</a>
        </form>
    </div>
</div>
@endsection