@extends('layouts.user.app')
@section('content')
    <div id="beranda" class=" section">
        <div class="container">
            <div class="row min-vh-100 min-vh-u-lg-85 align-items-start">
                    <div class="col-12 col-lg-6 hero-fit-vertical order-lg-2" data-aos="fade-right" id="container-image-hero">
                        <img class="img-fluid bg-body-tertiary rounded" id="sipakar-home"
                            src="{{ asset('assets/img/sipakar-home.png') }}" width="773" height="742"
                            alt="Gambar Home">
                </div>
                <div class="col-12 col-lg-6 align-self-center px-3 px-sm-5 order-lg-1" data-aos="fade-left" data-aos-anchor="body"
                    id="col2">
                    <h1 class="text-start font-bold " style="color: #001B48;">
                        Langkah Cerdas Cegah Hipertensi
                    </h1>
                    <p class="lead"> <b>TensiTrack</b> hadir sebagai platform cerdas untuk mengenali potensi risiko hipertensi yang dapat membantu anda mengambil langkah preventif demi kesehatan optimal.</p>
                </div>
            </div>
        </div>
    </div>
    <div id="alur-interaksi" class="section">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <h1 class="text-center font-bold " style="color: #001B48;">Alur Interaksi Pengguna</h1>
                <p style="color: #001B48;">Ikuti langkah sederhana berikut untuk mulai menggunakan TensiTrack</p>
                <img class="img-fluid" src="{{ asset('assets/img/Alur Kerja/alur-kerja.png') }}"
                    alt="Alur Interaksi Pengguna">
            </div>
        </div>
    </div>
            <!-- Tentang TensiTrack -->
            <div id="about" class="section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-6" data-aos="fade-right">
                            <h2 class="text-start font-bold">Tentang TensiTrack</h2>
                            <p class="text-muted mb-2">Solusi digital untuk skrining tekanan darah yang cepat dan akurat</p>

                            <p class="small text-muted">TensiTrack adalah platform sistem pakar berbasis web yang menggunakan metode <em>forward chaining</em> untuk membantu pengguna mengenali potensi risiko hipertensi sejak dini.</p>

                            <p class="small text-muted">Dikembangkan oleh tim ahli di bidang kesehatan dan teknologi, sistem ini mampu menganalisis faktor risiko yang dimasukkan pengguna, kemudian memberikan hasil skrining dan rekomendasi tindak lanjut sesuai dengan standar medis yang berlaku.</p>
                        </div>
                        <div class="col-12 col-lg-6 text-center" data-aos="fade-left">
                            <img src="{{ asset('logo-tensitrack.png') }}" alt="Logo TensiTrack" class="img-fluid rounded" width="360" height="360">
                        </div>
                    </div>
                </div>
            </div>

    <div id="diagnosis" class="section">
        <div class="container">
            <h2 class="font-semibold pb-3" data-aos="fade-up">
                Diagnosis
            </h2>
            <div class="row">
                <div class="col-12" data-aos="fade-up">
                    <div class="card shadow border border-0">
                        <div class="card-body">
                            <div class="card-text">
                                Sistem ini menggunakan metode forward chaining untuk mendiagnosis penyakit. Proses dimulai
                                dengan mengevaluasi gejala yang diberikan oleh pengguna, kemudian sistem mencocokkannya
                                dengan aturan yang ada. Jika terdapat aturan yang terpenuhi, sistem akan memberikan detail
                                hasil diagnosis penyakit. Detail hasil diagnosis penyakit akan disimpan dalam sistem.
                                Pengguna dapat melihat kembali detail hasil diagnosis yang telah dilakukan pada histori
                                diagnosis di menu profil.
                            </div>
                            <div class="d-grid pt-3">
                                <button id="btn-diagnosis" class="btn btn-custom1 py-2">
                                    Mulai Diagnosis Penyakit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if (Auth::check() && Gate::check('asUser'))
        @section('title', auth()->user()->name . html_entity_decode(' &mdash;'))
        @include('user.profile-modal')
        @include('user.detail-diagnosis-modal')
        @push('styleLibraries')
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
            <link rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        @endpush
        @push('scriptPerPage')
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
            <script src="{{ asset('assets/js/user/profile-select-manager.js') }}"></script>
            <script src="{{ asset('assets/js/user/profile-modal.js') }}"></script>
            <script src="{{ asset('assets/js/user/detail-diagnosis-modal.js') }}"></script>
        @endpush
    @endif
@endsection

@push('scriptPerPage')
    <script type="text/javascript">
        const isUser = @json(Auth::check() && Gate::check('asUser'));
        const hasUserProfile = @json(Auth::user()->profile->id ?? false);
        let login = @json(session('success') ?? false);
        const csrfToken = '{{ csrf_token() }}';
        const penyakitImage = @json($penyakit);
        const assetStoragePenyakit = '{{ asset('/storage/penyakit/') }}';
        const assetStorageGejala = '{{ asset('/storage/gejala/') }}';
    </script>
@endpush
