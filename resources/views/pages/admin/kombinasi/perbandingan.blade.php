@extends('layouts.admin')
@section('content')
<div class="container-fluid px-4">
    <div class="row align-items-center">
        <div class="col-sm-6 col-md-8">
            <h1 class="mt-4">{{ $title }}</h1>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive col-lg-12">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col" class="text-center">Kriteria Pertama</th>
                            <th scope="col" class="text-center">Intensitas Kepentingan</th>
                            <th scope="col" class="text-center">Kriteria Kedua</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($details))
                        <form action="{{ route('kombinasi.update', $details[0]->criteria_analysis_id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="id" value="{{ $details[0]->criteria_analysis_id }}">
                            @foreach ($details as $detail)
                            <tr>
                                <input type="hidden" name="criteria_analysis_detail_id[]" value="{{ $detail->id }}">
                                <td class="text-center">
                                    {{ $detail->firstCriteria->nama_kriteria }}
                                </td>
                                <td class="text-center">
                                    <select class="form-select" name="comparison_values[]" required>
                                        <option value="" disabled selected>--Pilih Nilai--</option>
                                        <option value="1" {{ $detail->comparison_value == 1 ? 'selected' : '' }}>
                                            1 - Sama Pentingnya
                                        </option>
                                        <option value="2" {{ $detail->comparison_value == 2 ? 'selected' : '' }}>
                                            2 - Mendekati Sedikit Lebih Penting
                                        </option>
                                        <option value="3" {{ $detail->comparison_value == 3 ? 'selected' : '' }}>
                                            3 - Sedikit Lebih Penting
                                        </option>
                                        <option value="4" {{ $detail->comparison_value == 4 ? 'selected' : '' }}>
                                            4 - Mendekati Lebih Penting
                                        </option>
                                        <option value="5" {{ $detail->comparison_value == 5 ? 'selected' : '' }}>
                                            5 - Lebih Penting
                                        </option>
                                        <option value="6" {{ $detail->comparison_value == 6 ? 'selected' : '' }}>
                                            6 - Mendekati Sangat Penting
                                        </option>
                                        <option value="7" {{ $detail->comparison_value == 7 ? 'selected' : '' }}>
                                            7 - Sangat Penting
                                        </option>
                                        <option value="8" {{ $detail->comparison_value == 8 ? 'selected' : '' }}>
                                            8 - Mendekati Mutlak Sangat Penting
                                        </option>
                                        <option value="9" {{ $detail->comparison_value == 9 ? 'selected' : '' }}>
                                            9 - Mutlak Sangat Penting
                                        </option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    {{ $detail->secondCriteria->nama_kriteria }}
                                </td>
                            </tr>
                            @endforeach
                            <div class="col-lg-12">
                                @can('admin')
                                <form action="{{ route('kombinasi.update', $criteria_analysis) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn mb-3 ml-4 btn-primary">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        Simpan
                                    </button>
                                    @endcan
                                    @if ($isDoneCounting)
                                    <a href="{{ route('kombinasi.index', $criteria_analysis->id) }}" class="btn btn-success mb-3">
                                        <i class="fa-solid fa-eye"></i>
                                        Hasil
                                    </a>
                                    @else
                                    <a class="btn btn-success disabled mb-3">
                                        <i class="fa-solid fa-eye"></i>
                                        Admin Belum Menyimpan Kriteria
                                    </a>
                                    @endif
                                </form>
                            </div>
                        </form>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection