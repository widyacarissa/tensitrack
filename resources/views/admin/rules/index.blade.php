@extends('layouts.admin.app')

@push('cssLibraries')
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('title', 'Manajemen Aturan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Aturan (Rule Engine)</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.beranda') }}">Beranda</a></div>
                <div class="breadcrumb-item">Manajemen Aturan</div>
            </div>
        </div>

        <div class="section-body">
            <div class="pb-4">
                <a href="{{ route('admin.rules.create') }}" class="btn btn-primary" type="button">
                    <i class="fas fa-plus"></i> Tambah Aturan Baru
                </a>
                <a href="#" id="cetak-data-btn" class="btn btn-warning text-dark" type="button">
                    <i class="fas fa-print"></i> Cetak Data
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Aturan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="ruleTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Aturan</th>
                                    <th>Tingkat Risiko Hasil</th>
                                    <th class="text-center">Prioritas</th>
                                    <th>Terakhir Diperbarui</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rules as $key => $rule)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $rule->name }}</td>
                                        <td><span class="badge bg-success">{{ $rule->tingkatRisiko->kode ?? '' }}</span> {{ $rule->tingkatRisiko->tingkat_risiko ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $rule->priority }}</td>
                                        <td>{{ $rule->updated_at->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                                                                 <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton-{{ $rule->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                                                    Aksi
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $rule->id }}">
                                                    <a class="dropdown-item" href="{{ route('admin.rules.show', $rule->id) }}">
                                                        <i class="fas fa-eye me-2"></i> Detail
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('admin.rules.edit', $rule->id) }}">
                                                        <i class="fas fa-edit me-2"></i> Edit
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger delete-rule-btn" href="#" data-rule-id="{{ $rule->id }}">
                                                        <i class="fas fa-trash me-2"></i> Hapus
                                                    </a>
                                                    <form id="delete-form-{{ $rule->id }}" action="{{ route('admin.rules.destroy', $rule->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('jsLibraries')
    <script src="{{ asset('assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
@endpush

@push('jsCustom')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#ruleTable').DataTable();

        $('#cetak-data-btn').on('click', function(e){
            e.preventDefault();
            // Since there is no PDF route yet, show an alert.
            swal('Fitur Dalam Pengembangan', 'Fitur untuk mencetak data aturan akan segera tersedia.', 'info');
        });

        $('.delete-rule-btn').on('click', function(e) {
            e.preventDefault();
            const ruleId = $(this).data('rule-id');
            
            swal({
                title: 'Apakah Anda yakin?',
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan aturan ini!",
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#delete-form-' + ruleId).submit();
                }
            });
        });
    });
</script>
@endpush