@extends('layouts.admin.app')

@push('cssLibraries')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">
    
    <style>
        /* --- PALET WARNA (Sama dengan halaman Create) --- 
           Primary: #001B48 (Biru Dongker/Gelap)
           Accent:  #E49502 (Oranye Emas)
           White:   #FFFFFF
        */

        .gejala-container {
            max-height: 400px;
            overflow-y: auto;
            background: #fbfbfb;
            padding: 10px;
            border: 1px solid #e4e6fc;
            border-radius: 8px;
        }

        /* 1. KARTU NORMAL */
        .gejala-card {
            display: block;
            position: relative;
            background: #FFFFFF;
            border: 1px solid #e4e6fc;
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }

        .gejala-card:hover {
            border-color: #E49502; 
            background-color: #fffbf0; 
            transform: translateX(2px);
        }

        .gejala-checkbox {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* 2. KARTU TERPILIH (ACTIVE) */
        .gejala-card.active {
            background-color: #001B48 !important;
            border-color: #E49502 !important;
            color: #FFFFFF;
            box-shadow: 0 4px 8px rgba(0, 27, 72, 0.3);
            transform: translateY(-2px);
        }

        .gejala-card.active .gejala-text {
            color: #FFFFFF !important;
            font-weight: 600;
        }

        .gejala-card.active .badge-kode {
            background-color: #E49502;
            color: #001B48;
        }

        .gejala-card.active .check-icon {
            display: inline-block;
            color: #E49502;
            background: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            font-size: 12px;
        }

        /* --- ELEMENT DALAM KARTU --- */
        .card-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .info-wrapper {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .badge-kode {
            font-size: 13px;
            font-weight: 700;
            background-color: #e3eaef;
            color: #001B48;
            padding: 5px 10px;
            border-radius: 4px;
            margin-right: 12px;
            min-width: 50px;
            text-align: center;
            transition: 0.3s;
        }

        .gejala-text {
            font-size: 14px;
            color: #333;
            line-height: 1.4;
            font-weight: 500;
            transition: 0.2s;
        }

        .check-icon {
            display: none;
        }

        /* Search & Button Styles */
        .search-box {
            height: 45px;
            border-radius: 6px;
            border: 1px solid #e4e6fc;
        }
        .search-box:focus {
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
            <h1>Ubah Aturan untuk Penyakit: {{ $penyakit->name }}</h1>
        </div>
        <div class="section-body">
            <div class="pb-4">
                <a href="{{ route('admin.rule') }}" class="btn btn-secondary px-3 py-2">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.rule.update', $penyakit->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        
                        {{-- Bagian Penyakit --}}
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold" style="color: #001B48; font-size: 16px;">Penyakit</label>
                            <input type="text" class="form-control" value="{{ $penyakit->name }}" readonly>
                            <p class="text-muted small mt-2">Anda sedang mengubah aturan untuk penyakit ini. Penyakit tidak dapat diubah di halaman ini.</p>
                        </div>

                        {{-- Bagian Gejala (Format Kartu) --}}
                        <div class="form-group">
                            <label class="form-label font-weight-bold" style="color: #001B48; font-size: 16px;">Gejala</label>
                            <p class="text-muted small mb-3">
                                Klik kotak gejala untuk mengubah pilihan. Gejala yang dipilih ditandai dengan warna <b style="color: #001B48">Biru Gelap</b>.
                            </p>
                            
                            {{-- Search Bar --}}
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search" style="color: #001B48"></i></span>
                                </div>
                                <input type="text" id="searchGejala" class="form-control search-box border-left-0" placeholder="Ketik untuk mencari gejala...">
                            </div>

                            {{-- Actions & Counter --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small font-weight-bold">
                                    Total Terpilih: <span id="totalSelected" style="color: #E49502; font-size: 16px; font-weight: 800;">0</span>
                                </span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary px-3" id="btnSelectAll">Pilih Semua</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger px-3" id="btnReset">Reset</button>
                                </div>
                            </div>

                            {{-- Container Gejala --}}
                            <div class="gejala-container" id="gejalaContainer">
                                @foreach ($gejala as $g)
                                    @php
                                        $isChecked = in_array($g->id, $selectedGejala);
                                    @endphp

                                    <label class="gejala-card {{ $isChecked ? 'active' : '' }}" id="card_{{ $g->id }}">
                                        <input type="checkbox" 
                                               class="gejala-checkbox" 
                                               name="gejala[]" 
                                               value="{{ $g->id }}"
                                               id="input_{{ $g->id }}"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        
                                        <div class="card-flex">
                                            <div class="info-wrapper">
                                                <span class="badge-kode">G{{ $g->id }}</span>
                                                <span class="gejala-text">{{ $g->name }}</span>
                                            </div>
                                            <div class="check-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('gejala')
                                <div class="text-danger mt-2 small font-weight-bold">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-custom-primary btn-lg btn-block shadow-sm" style="border-radius: 50px;">
                                <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('jsLibraries')
    <script src="{{ asset('assets/vendor/select2/dist/js/select2.full.min.js') }}"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchGejala');
            const cards = document.querySelectorAll('.gejala-card');
            const inputs = document.querySelectorAll('.gejala-checkbox');
            const totalDisplay = document.getElementById('totalSelected');

            // Update angka total saat halaman pertama kali dimuat
            updateTotal();

            function updateCardVisual(input, card) {
                if(input.checked) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
                updateTotal();
            }

            function updateTotal() {
                const checked = document.querySelectorAll('.gejala-checkbox:checked').length;
                totalDisplay.innerText = checked;
            }

            inputs.forEach(input => {
                const card = document.getElementById('card_' + input.value);
                
                // Event listener saat diklik
                input.addEventListener('change', function() {
                    updateCardVisual(this, card);
                });
            });

            // Fitur Pencarian
            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                cards.forEach(card => {
                    const text = card.innerText.toLowerCase();
                    card.style.display = text.includes(term) ? 'block' : 'none';
                });
            });

            // Tombol Pilih Semua
            document.getElementById('btnSelectAll').addEventListener('click', function() {
                cards.forEach(card => {
                    if(card.style.display !== 'none') {
                        const input = card.querySelector('.gejala-checkbox');
                        if(!input.checked) {
                            input.checked = true;
                            updateCardVisual(input, card);
                        }
                    }
                });
            });

            // Tombol Reset
            document.getElementById('btnReset').addEventListener('click', function() {
                inputs.forEach(input => {
                    if(input.checked) {
                        input.checked = false;
                        const card = document.getElementById('card_' + input.value);
                        updateCardVisual(input, card);
                    }
                });
            });
        });
    </script>
@endpush