@extends('layouts.admin.app')

@push('cssLibraries')
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    
    <style>
        /* Styling untuk badge faktor risiko agar rapi */
        .symptom-badge {
            display: inline-block;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 4px;
            font-size: 12px;
            background-color: #f0f2f5;
            border: 1px solid #e4e6fc;
            color: #333;
        }
        .symptom-code {
            font-weight: bold;
            color: #001B48; /* Menyesuaikan tema warna Anda */
            margin-right: 5px;
        }
    </style>
@endpush

@push('jsLibraries')
    <script src="{{ asset('assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
@endpush

@push('jsCustom')
    <script>
        const table = document.getElementById('table-1');
        const dataTable = $(table).DataTable({});
        $(document).on("click", ".btnHapus", function(e) {
            e.preventDefault();
            var formId = $(this).data('form-id');
            var form = $("#" + formId);
            swal({
                    title: "Apakah Anda yakin?",
                    text: "Semua aturan faktor risiko untuk tingkat risiko ini akan dihapus!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) form.submit();
                });
        });
    </script>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Aturan</h1>
        </div>
        <div class="section-body">
            <div class="pb-4">
                <a href="{{ route('admin.rule.tambah') }}" class="btn btn-primary" type="button">
                    <i class="fas fa-plus mr-2"></i> Tambah Data
                </a>
                <a href="{{ route('rule.pdf') }}" target="_blank" class="btn btn-warning text-dark" type="button">
                    <i class="fas fa-print mr-2"></i> Cetak Data
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session('success') }}
                    </div>
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>×</span></button>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-1">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th width="20%">Tingkat Risiko</th>
                                    <th width="50%">Daftar Faktor Risiko (Aturan)</th>
                                    <th width="15%">Update Terakhir</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- LOGIKA PENGELOMPOKAN DATA --}}
                                @php
                                    $groupedRules = collect($rules)->groupBy('tingkatRisiko.name');
                                @endphp

                                @foreach ($groupedRules as $tingkatRisikoName => $groupItems)
                                    @php
                                        $firstItem = $groupItems->first();
                                        $tingkatRisikoId = $firstItem['tingkatRisiko']['id'];
                                        $latestUpdate = $groupItems->max('updated_at'); 
                                        $uniqueFaktorRisiko = $groupItems->unique('no_faktor_risiko');
                                    @endphp

                                    <tr>
                                        <td class="text-center align-top">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="align-top font-weight-bold">
                                            {{ $tingkatRisikoName }}
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap">
                                                @foreach ($uniqueFaktorRisiko as $item)
                                                    <span class="symptom-badge">
                                                        <span class="symptom-code">{{ $item['no_faktor_risiko'] }}</span>
                                                        {{ $item['faktorRisiko']['name'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="align-top text-small">
                                            {{ \Carbon\Carbon::parse($latestUpdate)->format('d M Y H:i') }}
                                        </td>
                                        <td class="align-top">
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                    id="dropdownMenuButton-{{ $tingkatRisikoId }}" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Aksi
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $tingkatRisikoId }}">
                                                    <a class="dropdown-item has-icon text-warning"
                                                        href="{{ route('admin.rule.edit', ['tingkatRisiko' => $tingkatRisikoId]) }}">
                                                        <i class="far fa-edit"></i> Edit
                                                    </a>
                                                    
                                                    <a class="dropdown-item has-icon text-danger btnHapus" href="#" data-form-id="formHapus-{{ $tingkatRisikoId }}">
                                                        <i class="far fa-trash-alt"></i> Hapus
                                                    </a>
                                                    
                                                    <form id="formHapus-{{ $tingkatRisikoId }}"
                                                        action="{{ route('admin.rule.destroy', ['tingkatRisiko' => $tingkatRisikoId]) }}"
                                                        method="post" style="display: none;">
                                                        @csrf
                                                        @method('delete')
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