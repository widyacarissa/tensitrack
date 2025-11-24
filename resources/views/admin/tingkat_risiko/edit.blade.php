@extends('layouts.admin.app')

@push('cssLibraries')
    <style>
        .form-label {
            color: #001B48;
            font-weight: bold;
            font-size: 16px;
        }
        .form-control {
            height: 45px;
            border-radius: 6px;
            border: 1px solid #e4e6fc;
        }
        .form-control:focus {
            border-color: #001B48;
            box-shadow: 0 0 0 0.2rem rgba(0, 27, 72, 0.15);
        }
        .btn-custom-primary {
            background-color: #001B48;
            border-color: #001B48;
            color: #FFFFFF;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 50px;
        }
        .btn-custom-primary:hover {
            background-color: #001333;
            color: #E49502;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Edit Tingkat Risiko Kode {{ $tingkat_risiko->kode }}</h1>
        </div>
        <div class="section-body">
            <div class="pb-4">
                <a href="{{ route('admin.tingkat-risiko') }}" class="btn btn-secondary px-3 py-2">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.tingkat-risiko.update', ['id' => $tingkat_risiko->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-4">
                            <label class="form-label">Kode</label>
                            <input type="text" class="form-control" value="{{ $tingkat_risiko->kode }}" readonly>
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label">Tingkat Risiko</label>
                            <input type="text" required class="form-control @error('tingkat_risiko') is-invalid @enderror"
                                name="tingkat_risiko" id="tingkatRisiko"
                                value="{{ old('tingkat_risiko', $tingkat_risiko->tingkat_risiko) }}"
                                placeholder="Masukkan Tingkat Risiko (contoh: Rendah, Sedang, Tinggi)">
                            @error('tingkat_risiko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label">Keterangan</label>
                            <textarea required class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                id="keterangan" rows="3" placeholder="Masukkan keterangan untuk tingkat risiko ini...">{{ old('keterangan', $tingkat_risiko->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label">Saran</label>
                            <textarea required class="form-control @error('saran') is-invalid @enderror" name="saran"
                                id="saran" rows="3" placeholder="Berikan saran untuk tingkat risiko ini...">{{ old('saran', $tingkat_risiko->saran) }}</textarea>
                            @error('saran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-custom-primary btn-lg btn-block shadow-sm">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('jsLibraries')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const keteranganTextarea = document.getElementById('keterangan');
        const saranTextarea = document.getElementById('saran');

        function autoResizeTextarea(textarea) {
            textarea.style.height = 'auto'; // Reset height to recalculate
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        // Initial resize on page load
        autoResizeTextarea(keteranganTextarea);
        autoResizeTextarea(saranTextarea);

        // Resize on input
        keteranganTextarea.addEventListener('input', function() {
            autoResizeTextarea(this);
        });
        saranTextarea.addEventListener('input', function() {
            autoResizeTextarea(this);
        });
    });
</script>
@endpush



