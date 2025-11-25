@extends('layouts.admin.app')

@section('title', 'Detail Aturan')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>Detail Aturan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.beranda') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.rules.index') }}">Manajemen Aturan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Aturan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card p-3 shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title text-primary">{{ $rule->name }}</h4>
            <p class="card-text">{{ $rule->description ?? 'Tidak ada deskripsi.' }}</p>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <strong>Tingkat Risiko Hasil:</strong>
                    <p><span class="badge bg-success">{{ $rule->tingkatRisiko->kode }}</span> {{ $rule->tingkatRisiko->tingkat_risiko }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Prioritas:</strong>
                    <p><span class="badge bg-info">{{ $rule->priority }}</span> (Aturan dengan prioritas lebih tinggi dievaluasi terlebih dahulu)</p>
                </div>
            </div>
        </div>

        <div class="card-footer bg-transparent">
            <h5 class="mb-3">Logika Aturan:</h5>
            @forelse ($rule->conditionGroups as $groupIndex => $group)
                @if ($groupIndex > 0)
                    <div class="text-center my-3">
                        <span class="badge bg-secondary fs-6">OR</span>
                    </div>
                @endif
                <div class="card border-primary mb-3">
                    <div class="card-header bg-primary text-white">
                        Grup Kondisi #{{ $groupIndex + 1 }} (Semua kondisi di bawah ini harus terpenuhi - <span class="fw-bold">AND</span>)
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse ($group->conditions as $conditionIndex => $condition)
                            <li class="list-group-item d-flex flex-wrap align-items-center">
                                <strong class="me-2">Kondisi #{{ $conditionIndex + 1 }}:</strong>
                                <span class="badge bg-primary me-2">{{ $condition->type }}</span>
                                @if($condition->faktorRisiko)
                                    <span class="me-2 text-truncate" title="{{ $condition->faktorRisiko->name }}">
                                        (Faktor: <span class="fw-bold">{{ $condition->faktorRisiko->kode }}</span>)
                                    </span>
                                @endif
                                <span class="badge bg-dark me-2">{{ $condition->operator }}</span>
                                <span class="badge bg-warning text-dark">{{ $condition->value }}</span>
                            </li>
                        @empty
                            <li class="list-group-item">Tidak ada kondisi dalam grup ini.</li>
                        @endforelse
                    </ul>
                </div>
            @empty
                <div class="alert alert-warning">Tidak ada grup kondisi untuk aturan ini.</div>
            @endforelse
        </div>

        <div class="mt-4 p-3">
            <a href="{{ route('admin.rules.edit', $rule->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Edit Aturan</a>
            <a href="{{ route('admin.rules.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
        </div>
    </div>
@endsection