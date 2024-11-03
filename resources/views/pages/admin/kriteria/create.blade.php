@extends('layouts.admin')
@section('content')
<div class="container-fluid px-4 border-bottom">
    <h1 class="mt-4 h2">{{ $title }}</h1>
</div>
<div class="container-fluid px-4 mt-3">
    <div class="row align-items-center">
        <form class="col-lg-8" method="POST" action="{{ route('kriteria.index') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror" id="nama_kriteria" name="nama_kriteria" value="{{ old('nama_kriteria') }}" autofocus required placeholder="Masukkan Kriteria">
                @error('nama_kriteria')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="BENEFIT" {{ old('kategori') == 'BENEFIT' ? 'selected' : '' }}>Benefit</option>
                    <option value="COST" {{ old('kategori') == 'COST' ? 'selected' : '' }}>Cost</option>
                </select>
                @error('kategori')
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
            <div class="mb-3">
                <label for="skala1" class="form-label">Sub Kriteria Skala 1</label>
                <input type="text" class="form-control @error('skala1') is-invalid @enderror" id="skala1" name="skala1" value="{{ old('skala1') }}" autofocus required placeholder="Masukkan Sub Kriteria">
                @error('skala1')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="skala2" class="form-label">Sub Kriteria Skala 2</label>
                <input type="text" class="form-control @error('skala2') is-invalid @enderror" id="skala2" name="skala2" value="{{ old('skala2') }}" autofocus required placeholder="Masukkan Sub Kriteria">
                @error('skala2')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="skala3" class="form-label">Sub Kriteria Skala 3</label>
                <input type="text" class="form-control @error('skala3') is-invalid @enderror" id="skala3" name="skala3" value="{{ old('skala3') }}" autofocus required placeholder="Masukkan Sub Kriteria">
                @error('skala3')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="skala4" class="form-label">Sub Kriteria Skala 4</label>
                <input type="text" class="form-control @error('skala4') is-invalid @enderror" id="skala4" name="skala4" value="{{ old('skala4') }}" autofocus required placeholder="Masukkan Sub Kriteria">
                @error('skala4')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="skala5" class="form-label">Sub Kriteria Skala 5</label>
                <input type="text" class="form-control @error('skala5') is-invalid @enderror" id="skala5" name="skala5" value="{{ old('skala5') }}" autofocus required placeholder="Masukkan Sub Kriteria">
                @error('skala5')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary mb-3">Simpan</button>
            <a href="{{ route('kriteria.index') }}" class="btn btn-danger mb-3">Cancel</a>
        </form>
    </div>
</div>
@endsection