@extends('layouts.admin')
@section('content')
    <div class="container-fluid px-4 border-bottom">
        <h1 class="mt-4 h2">{{ $title }}</h1>
    </div>
    <form class="col-lg-8 contianer-fluid px-4 mt-3" method="POST" action="{{ route('jenis.update', $jenises->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="jenis_name" class="form-label">Nama Jenis Wisata</label>
            <input type="text" id="jenis_name" name="jenis_name" class="form-control @error('jenis_name') is-invalid @enderror" value="{{ old('jenis_name', $jenises->jenis_name) }}" autofocus required placeholder="Masukkan Jenis Wisata">
            @error('jenis_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea id="keterangan" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" autofocus required placeholder="Masukkan Keterangan">{{ old('keterangan', $jenises->keterangan) }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mb-3">Update</button>
        <a href="{{ route('jenis.index') }}" class="btn btn-danger mb-3">Cancel</a>
    </form>
@endsection