@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Tambah Tingkat Risiko</h1>
        </div>
        <div class="section-body">
            <div class="pb-4">
                <a href="{{ route('admin.tingkat-risiko') }}" class="btn btn-secondary">Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.tingkat-risiko.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                            <input type="text" class="form-control @error('tingkat_risiko') is-invalid @enderror"
                                name="tingkat_risiko" id="tingkatRisiko" value="{{ old('tingkat_risiko') }}">
                            @error('tingkat_risiko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                id="keterangan" value="{{ old('keterangan') }}">
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="saran" class="form-label">Saran</label>
                            <textarea name="saran" class="form-control @error('saran') is-invalid @enderror" id="saran"
                                style="height: 200px">{{ old('saran') }}</textarea>
                            @error('saran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


