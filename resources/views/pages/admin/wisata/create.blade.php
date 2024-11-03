@extends('layouts.admin')
@section('content')
    <div class="container-fluid px-4 border-bottom">
        <h1 class="mt-4 h2">{{ $title }}</h1>
    </div>
    <form class="col-lg-8 contianer-fluid px-4 mt-3" method="POST" action="{{ route('wisata.index') }}" enctype="multipart/form-data">
        @csrf
        {{-- nama destinasi wisata --}}
        <div class="mb-3">
            <label for="nama_wisata" class="form-label">Nama Destinasi Wisata</label>
            <input type="text" class="form-control @error('nama_wisata') is-invalid @enderror" id="nama_wisata" name="nama_wisata" value="{{ old('nama_wisata') }}" autofocus required placeholder="Nama Destinasi Wisata">
            @error('nama_wisata')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- lokasi maps --}}
        <div class="mb-3">
            <label for="lokasi_maps" class="form-label">Link Google Maps</label>
            <input type="text" class="form-control @error('lokasi_maps') is-invalid @enderror" id="lokasi_maps" name="lokasi_maps" value="{{ old('lokasi_maps') }}" autofocus required placeholder="Link Google Maps">
            @error('lokasi_maps')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- keterangan --}}
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" autofocus required placeholder="Keterangan">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- fasilitas --}}
        <div class="mb-3">
            <label for="fasilitas" class="form-label">Fasilitas</label>
            <textarea class="form-control @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" autofocus required placeholder="Fasilitas">{{ old('fasilitas') }}</textarea>
            @error('fasilitas')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- biaya --}}
        <div class="mb-3">
            <label for="biaya" class="form-label">Biaya</label>
            <input type="number" class="form-control @error('biaya') is-invalid @enderror" id="biaya" name="biaya" value="{{ old('biaya') }}" autofocus required placeholder="Biaya" min="0">
            @error('biaya')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- situs --}}
        <div class="mb-3">
            <label for="situs" class="form-label">Situs</label>
            <input type="text" class="form-control @error('situs') is-invalid @enderror" id="situs" name="situs" value="{{ old('situs') }}" autofocus placeholder="Situs">
            @error('situs')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- jenis wisata --}}
        <div class="mb-3">
            <label for="jenis_id" class="form-label">Jenis Wisata</label>
            <select class="form-select @error('jenis_id') is-invalid @enderror" id="jenis_id" name="jenis_id" required>
                <option value="" disabled selected>Pilih Jenis Wisata</option>
                @foreach ($jenises as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->jenis_name }}</option>
                @endforeach
            </select>
            @error('jenis_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- akses informasi --}}
        <div class="mb-3">
            <label for="tampil" class="form-label">Akses Informasi</label>
            <select class="form-select @error('tampil') is-invalid @enderror" id="tampil" name="tampil" required>
                <option value="" disabled selected>Pilih Akses Informasi</option>
                <option value="0" {{ old('tampil') == '0' ? 'selected' : '' }}>Akses Belum Dibuka</option>
                <option value="1" {{ old('tampil') == '1' ? 'selected' : '' }}>Akses Tidak Publik</option>
                <option value="2" {{ old('tampil') == '2' ? 'selected' : '' }}>Akses Publik</option>
            </select>
            @error('tampil')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        {{-- upload foto --}}
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" autofocus required>
            @error('foto')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="validasi" value="2">
        <button type="submit" class="btn btn-primary mb-3">Simpan</button>
        <a href="{{ route('wisata.index') }}" class="btn btn-danger mb-3">Cancel</a>
    </form>
@endsection