@extends('layouts.admin.app')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Edit Tingkat Risiko No {{ $tingkatRisiko->id }}</h1>
        </div>
        <div class="section-body">
            <div class="pb-4">
                <a href="{{ route('admin.tingkat-risiko') }}" class="btn btn-secondary">Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.tingkat-risiko.update', ['id' => $tingkatRisiko->id]) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Nama Tingkat Risiko</label>
                            <input type="text" class="form-control @error('tingkatRisiko') is-invalid @enderror"
                                name="tingkatRisiko" id="tingkatRisiko" value="{{ old('tingkatRisiko', $tingkatRisiko->name) }}">
                            @error('tingkatRisiko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Penyebab</label>
                            <input type="text" class="form-control @error('reason') is-invalid @enderror" name="reason"
                                id="reason" value="{{ old('reason', $tingkatRisiko->reason) }}">
                            @error('reason')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Solusi</label>
                            <textarea name="solution" class="form-control @error('solution') is-invalid @enderror" id="solution"
                                style="height: 200px">{{ old('solution', $tingkatRisiko->solution) }}</textarea>
                            @error('solution')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <div class="card card-body mt-3">
                                <img class="img-fluid" width="300" id="imagePreview"
                                    src="{{ asset('storage/tingkat-risiko/' . $tingkatRisiko->image) }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('jsCustom')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('image');

            input.addEventListener('change', function(e) {
                var file = e.target.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = document.getElementById('imagePreview');
                    img.src = e.target.result;
                };

                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
