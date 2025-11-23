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
                            <label class="form-label">Tingkat Risiko</label>
                            <input type="text" class="form-control @error('tingkat_risiko') is-invalid @enderror"
                                name="tingkat_risiko" id="tingkatRisiko"
                                value="{{ old('tingkat_risiko', $tingkatRisiko->tingkat_risiko) }}">
                            @error('tingkat_risiko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                name="keterangan" id="keterangan"
                                value="{{ old('keterangan', $tingkatRisiko->keterangan) }}">
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Saran</label>
                            <textarea name="saran" class="form-control @error('saran') is-invalid @enderror" id="saran"
                                style="height: 200px">{{ old('saran', $tingkatRisiko->saran) }}</textarea>
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


