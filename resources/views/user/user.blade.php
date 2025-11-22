@extends('layouts.user.app')
@section('content')
    {{-- BAGIAN 1: BERANDA --}}
    <div id="beranda" class="section">
        <div class="container">
            <div class="row min-vh-100 min-vh-u-lg-85 align-items-start">
                <div class="col-12 col-lg-6 hero-fit-vertical order-lg-2" data-aos="fade-right" id="container-image-hero">
                    <img class="img-fluid bg-body-tertiary rounded" id="sipakar-home"
                        src="{{ asset('assets/img/sipakar-home.png') }}" width="773" height="742" alt="Gambar Home">
                </div>
                <div class="col-12 col-lg-6 align-self-center px-3 px-sm-5 order-lg-1" data-aos="fade-left"
                    data-aos-anchor="body" id="col2">
                    <h1 class="text-start font-bold " style="color: #001B48;">
                        Langkah Cerdas Cegah Hipertensi
                    </h1>
                    <p class="lead"> <b>TensiTrack</b> hadir sebagai platform cerdas untuk mengenali potensi risiko
                        hipertensi yang dapat membantu anda mengambil langkah preventif demi kesehatan optimal.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: ALUR INTERAKSI --}}
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

    {{-- BAGIAN BARU: KALKULATOR BMI (Disisipkan di sini) --}}
    <div id="kalkulator-bmi" class="section py-5">
        <div class="container">
            <div class="row align-items-center">
                {{-- Kolom Kiri: Penjelasan & Gambar --}}
                <div class="col-12 col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <h4 class="fw-bold mb-3" style="color: #f59e0b;">Apa itu BMI?</h4>
                    <p class="text-muted mb-4">
                        Body Mass Index (BMI) adalah cara menghitung berat badan ideal berdasarkan tinggi dan berat badan. BMI
                        juga dapat dibedakan berdasarkan usia.
                    </p>

                    <h4 class="fw-bold mb-3" style="color: #f59e0b;">Apa itu kalkulator BMI?</h4>
                    <p class="text-muted mb-4">
                        Kalkulator BMI adalah alat untuk mengidentifikasi apakah berat badan kamu termasuk dalam kategori
                        ideal atau tidak. Kalkulator ini dapat digunakan oleh seseorang yang berusia 20 tahun ke atas.
                    </p>

                    <div class="text-center text-lg-start mt-4">
                        {{-- Gambar sesuai request user --}}
                        <img src="https://img.freepik.com/premium-vector/weight-loss-bmi-man-woman-before-after-diet-fitness-fat-thin-man-woman_162329-342.jpg"
                            alt="Ilustrasi BMI" class="img-fluid rounded shadow-sm"
                            style="max-height: 300px; object-fit: contain;">
                    </div>
                </div>

                {{-- Kolom Kanan: Form Kalkulator (Kartu Biru) --}}
                <div class="col-12 col-lg-6" data-aos="fade-left">
                    <div class="card border-0 shadow-lg p-4" style="background-color: #001B48; border-radius: 15px;">
                        <div class="card-body text-white">
                            {{-- Radio Button Gender --}}
                            <div class="d-flex justify-content-center gap-4 mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderLaki"
                                        value="male" checked>
                                    <label class="form-check-label" for="genderLaki">Laki-Laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderPerempuan"
                                        value="female">
                                    <label class="form-check-label" for="genderPerempuan">Perempuan</label>
                                </div>
                            </div>

                            {{-- Input Tinggi --}}
                            <div class="mb-3">
                                <label for="height" class="form-label fw-bold" style="color: #f59e0b;">Tinggi Badan
                                    (cm)</label>
                                <input type="number" class="form-control p-3" id="height"
                                    placeholder="Masukkan tinggi badan..." style="border-radius: 10px;">
                            </div>

                            {{-- Input Berat --}}
                            <div class="mb-4">
                                <label for="weight" class="form-label fw-bold" style="color: #f59e0b;">Berat Badan
                                    (kg)</label>
                                <input type="number" class="form-control p-3" id="weight"
                                    placeholder="Masukkan berat badan..." style="border-radius: 10px;">
                            </div>

                            {{-- Tombol Hitung --}}
                            <div class="d-grid gap-2 mb-4">
                                <button type="button" onclick="calculateBMI()" class="btn btn-light fw-bold py-3"
                                    style="color: #001B48; border-radius: 10px;">
                                    Menghitung BMI
                                </button>
                            </div>

                            {{-- Hasil --}}
                            <div class="mb-2">
                                <label for="result" class="form-label fw-bold" style="color: #f59e0b;">Hasil</label>
                                <input type="text" class="form-control p-3 fw-bold text-center" id="result" readonly
                                    style="border-radius: 10px; background-color: #fff;" placeholder="-">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 3: DIAGNOSIS --}}
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


    {{-- BAGIAN 6: FAQ (Pertanyaan Umum) --}}
    <div id="faq" class="section py-5 bg-white position-relative overflow-hidden">
        {{-- Decoration Circle --}}
        <div style="position: absolute; top: -50px; left: -50px; width: 150px; height: 150px; border-radius: 50%; background: radial-gradient(circle, rgba(245,158,11,0.1) 0%, rgba(255,255,255,0) 70%); pointer-events: none;"></div>

        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold" style="color: #001B48;">Pertanyaan Umum</h2>
                <p class="text-muted">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
            </div>

            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-lg-9">
                    <div class="accordion accordion-flush d-flex flex-column gap-3" id="accordionFAQ">
                        
                        {{-- Q1 --}}
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed fw-semibold shadow-sm rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" 
                                    style="color: #001B48; border: 1px solid #f59e0b; background-color: #fff;">
                                    Apakah hasil skrining risiko hipertensi pada TensiTrack dapat menggantikan pemeriksaan dokter?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted pt-2 px-4 pb-4 border-start border-end border-bottom border-warning-subtle rounded-bottom-3 bg-light bg-opacity-25">
                                    Tidak. TensiTrack hanya membantu memberikan indikasi awal terhadap risiko hipertensi, dan hasilnya tidak menggantikan diagnosis medis dari dokter.
                                </div>
                            </div>
                        </div>

                        {{-- Q2 --}}
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed fw-semibold shadow-sm rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" 
                                    style="color: #001B48; border: 1px solid #f59e0b; background-color: #fff;">
                                    Bagaimana cara kerja TensiTrack dalam melakukan skrining risiko hipertensi?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted pt-2 px-4 pb-4 border-start border-end border-bottom border-warning-subtle rounded-bottom-3 bg-light bg-opacity-25">
                                    Sistem TensiTrack menggunakan metode forward chaining untuk menganalisis data pengguna dan menentukan potensi risiko hipertensi berdasarkan faktor-faktor kesehatan tertentu.
                                </div>
                            </div>
                        </div>

                        {{-- Q3 --}}
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed fw-semibold shadow-sm rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" 
                                    style="color: #001B48; border: 1px solid #f59e0b; background-color: #fff;">
                                    Apakah kalkulator BMI dapat digunakan oleh seluruh kelompok masyarakat?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted pt-2 px-4 pb-4 border-start border-end border-bottom border-warning-subtle rounded-bottom-3 bg-light bg-opacity-25">
                                    Ya, kalkulator BMI dapat digunakan oleh siapa saja, tetapi hasilnya perlu disesuaikan dengan kondisi individu seperti usia dan massa otot.
                                </div>
                            </div>
                        </div>

                        {{-- Q4 --}}
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed fw-semibold shadow-sm rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" 
                                    style="color: #001B48; border: 1px solid #f59e0b; background-color: #fff;">
                                    Apakah data kesehatan saya aman?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted pt-2 px-4 pb-4 border-start border-end border-bottom border-warning-subtle rounded-bottom-3 bg-light bg-opacity-25">
                                    TensiTrack menerapkan sistem keamanan data dengan enkripsi agar informasi pribadi pengguna tetap terlindungi.
                                </div>
                            </div>
                        </div>

                        {{-- Q5 --}}
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed fw-semibold shadow-sm rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" 
                                    style="color: #001B48; border: 1px solid #f59e0b; background-color: #fff;">
                                    Apakah skrining risiko hipertensi dapat dilakukan secara berkala?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted pt-2 px-4 pb-4 border-start border-end border-bottom border-warning-subtle rounded-bottom-3 bg-light bg-opacity-25">
                                    Ya, pengguna disarankan melakukan skrining secara berkala untuk memantau perubahan risiko dan menjaga kesehatan secara berkelanjutan.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN MODALS & SCRIPTS USER --}}
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
    {{-- SCRIPT BAWAAN --}}
    <script type="text/javascript">
        const isUser = @json(Auth::check() && Gate::check('asUser'));
        const hasUserProfile = @json(Auth::user()->profile->id ?? false);
        let login = @json(session('success') ?? false);
        const csrfToken = '{{ csrf_token() }}';
        const penyakitImage = @json($penyakit);
        const assetStoragePenyakit = '{{ asset('/storage/penyakit/') }}';
        const assetStorageGejala = '{{ asset('/storage/gejala/') }}';
    </script>

    {{-- SCRIPT TAMBAHAN UNTUK KALKULATOR BMI --}}
    <script>
        function calculateBMI() {
            // Ambil nilai input
            const height = document.getElementById('height').value;
            const weight = document.getElementById('weight').value;
            const resultInput = document.getElementById('result');

            // Validasi input
            if (height === "" || weight === "" || height <= 0 || weight <= 0) {
                resultInput.value = "Masukkan data yang valid!";
                return;
            }

            // Rumus BMI: Berat (kg) / (Tinggi (m) * Tinggi (m))
            const heightInMeters = height / 100;
            const bmi = (weight / (heightInMeters * heightInMeters)).toFixed(1);

            // Tentukan Kategori
            let category = "";
            if (bmi < 18.5) {
                category = "Kurus (Underweight)";
            } else if (bmi >= 18.5 && bmi <= 24.9) {
                category = "Ideal (Normal)";
            } else if (bmi >= 25 && bmi <= 29.9) {
                category = "Gemuk (Overweight)";
            } else {
                category = "Obesitas";
            }

            // Tampilkan Hasil
            resultInput.value = `${bmi} - ${category}`;
        }
    </script>
@endpush