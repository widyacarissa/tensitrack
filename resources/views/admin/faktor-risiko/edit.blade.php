@extends('layouts.admin.app')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Edit Faktor Risiko No {{ $faktorRisiko->id }}</h1>
        </div>
        <div class="section-body">
            <div class="pb-4">
                <a href="{{ route('admin.faktor-risiko') }}" class="btn btn-secondary">Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.faktor-risiko.update', ['id' => $faktorRisiko->id]) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Faktor Risiko</label>
                            <input type="text" class="form-control @error('faktorRisiko') is-invalid @enderror" name="faktorRisiko"
                                id="faktorRisiko" required value="{{ old('faktorRisiko', $faktorRisiko->name) }}">
                            @error('faktorRisiko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" required class="form-control @error('image') is-invalid @enderror"
                                name="image" id="image">
                            @error('faktorRisiko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="card card-body mt-3">
                                <img class="img-fluid" width="300" id="imagePreview"
                                    src="{{ asset('storage/faktor-risiko/' . $faktorRisiko->image) }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
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
