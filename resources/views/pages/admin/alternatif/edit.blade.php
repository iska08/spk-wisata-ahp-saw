@extends('layouts.admin')
@section('content')
<div class="container-fluid px-4 border-bottom">
    <h1 class="mt-4 h2">{{ $title }}</h1>
</div>
<form class="col-lg-8 contianer-fluid px-4 mt-3" method="POST" action="{{ route('alternatif.update', $alternatives->id) }}">
    @method('PUT')
    @csrf
    <fieldset disabled>
        <div class="row">
            <div class="mb-3 col-lg-6">
                <label for="nama_wisata" class="form-label">Destinasi Wisata yang Dipilih</label>
                <input type="text" class="form-control" id="nama_wisata" value="{{ old('nama_wisata', $alternatives->nama_wisata) }}" readonly required>
                @error('nama_wisata')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mb-3 col-lg-6">
                <label for="name" class="form-label">Jenis Wisata</label>
                <input type="text" class="form-control" id="name" value="{{ old('name', $alternatives->jenis->jenis_name) }}" readonly required>
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </fieldset>
    @foreach ($alternatives->alternatives as $value)
    <div class="mb-3">
        <input type="hidden" name="criteria_id[]" value="{{ $value->criteria->id }}">
        <input type="hidden" name="alternative_id[]" value="{{ $value->id }}">
        <label for="{{ str_replace(' ', '', $value->criteria->nama_kriteria) }}" class="form-label">
            Nilai <b>{{ $value->criteria->nama_kriteria }}</b>
        </label>
        <select class="form-select @error('alternative_value') 'is-invalid' : '' @enderror" id="{{ str_replace(' ', '', $value->criteria->nama_kriteria) }}" name="alternative_value[]" required>
            <option disabled value="">-- Pilih Sub Kriteria --</option>
            <option value="1" {{ $value->alternative_value == 1 ? 'selected' : '' }}>(Skala 1) -
                {{ $value->criteria->skala1 }}</option>
            <option value="2" {{ $value->alternative_value == 2 ? 'selected' : '' }}>(Skala 2) -
                {{ $value->criteria->skala2 }}</option>
            <option value="3" {{ $value->alternative_value == 3 ? 'selected' : '' }}>(Skala 3) -
                {{ $value->criteria->skala3 }}</option>
            <option value="4" {{ $value->alternative_value == 4 ? 'selected' : '' }}>(Skala 4) -
                {{ $value->criteria->skala4 }}</option>
            <option value="5" {{ $value->alternative_value == 5 ? 'selected' : '' }}>(Skala 5) -
                {{ $value->criteria->skala5 }}</option>
        </select>
        @error('alternative_value')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    @endforeach
    @if ($newCriterias->count())
    <input type="hidden" name="new_wisata_id" value="{{ $alternatives->id }}">
    <input type="hidden" name="new_jenis_id" value="{{ $alternatives->jenis_id }}">
    @foreach ($newCriterias as $value)
    <div class="mb-3">
        <input type="hidden" name="new_criteria_id[]" value="{{ $value->id }}">
        <label for="{{ str_replace(' ', '', $value->name) }}" class="form-label">
            Nilai <b>{{ $value->name }}</b>
        </label>
        <input type="text" id="{{ str_replace(' ', '', $value->name) }}" class="form-control @error('new_alternative_value') 'is-invalid' : '' @enderror" name="new_alternative_value[]" placeholder="Enter the value" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57)|| event.charCode == 46)" maxlength="3" autocomplete="off" required>
        @error('new_alternative_value')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    @endforeach
    @endif
    <button type="submit" class="btn btn-primary mb-3">Simpan Perubahan</button>
    <a href="/dashboard/alternatif" class="btn btn-danger mb-3">Cancel</a>
</form>
@endsection