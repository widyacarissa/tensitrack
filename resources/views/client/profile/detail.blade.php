@extends('layouts.app')

@section('title', 'Hasil Skrining')

@section('content')

<!-- Tentukan Warna & Ikon Berdasarkan 4 Kategori -->
@php 
    $level = strtolower($screening->result_level);

    if (stripos($level, 'tinggi') !== false) {
        $bgClass = 'bg-red-50'; $textClass = 'text-red-600'; $borderClass = 'border-red-200'; $icon = 'alert-triangle';
    } elseif (stripos($level, 'sedang') !== false) {
        $bgClass = 'bg-orange-50'; $textClass = 'text-orange-600'; $borderClass = 'border-orange-200'; $icon = 'alert-circle';
    } elseif (stripos($level, 'rendah') !== false) {
        $bgClass = 'bg-blue-50'; $textClass = 'text-blue-600'; $borderClass = 'border-blue-200'; $icon = 'info';
    } else {
        $bgClass = 'bg-green-50'; $textClass = 'text-green-600'; $borderClass = 'border-green-200'; $icon = 'shield-check';
    }
    
    // Extract bg color for stripe from text class (e.g. text-red-600 -> bg-red-600)
    $stripeClass = str_replace('text-', 'bg-', explode(' ', $textClass)[0]);
@endphp

<!-- Container Utama: Full Height minus Navbar (80px) -->
<div class="h-[calc(100vh-5rem)] bg-gray-50 p-4 overflow-hidden flex flex-col print:h-auto print:overflow-visible">
    
    <!-- Header Kecil (Breadcrumb + Aksi) -->
    <div class="flex justify-between items-center mb-4 flex-shrink-0 px-2 no-print">
        <a href="{{ route('client.profile.index') }}" class="inline-flex items-center text-gray-500 hover:text-[#001B48] transition font-bold text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
        </a>
        <div class="flex gap-2">
            <a href="{{ route('client.pdf.print', ['id' => $screening->id, 'action' => 'view']) }}" target="_blank" class="text-[#001B48] hover:text-[#E3943B] font-bold text-sm transition">Lihat PDF</a>
            <a href="{{ route('client.pdf.print', ['id' => $screening->id, 'action' => 'download']) }}" class="text-[#001B48] hover:text-[#E3943B] font-bold text-sm transition">Unduh PDF</a>
        </div>
    </div>

    <!-- Grid 3 Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 h-full overflow-hidden">
        
        <!-- KOLOM 1: PROFIL & HASIL (3 Kolom Grid) -->
        <div class="lg:col-span-3 flex flex-col gap-4 h-full overflow-y-auto custom-scrollbar pr-1">
            
            <!-- Kartu Hasil -->
            <div class="bg-white rounded-2xl shadow-sm border {{ $borderClass }} p-6 text-center flex flex-col items-center justify-center flex-shrink-0 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 {{ $stripeClass }}"></div>
                <div class="w-16 h-16 rounded-full {{ $bgClass }} flex items-center justify-center mb-3">
                    <i data-lucide="{{ $icon }}" class="w-8 h-8 {{ $textClass }}"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Risiko Hipertensi</p>
                <h2 class="text-2xl font-extrabold {{ $textClass }} leading-tight mt-1">
                    {{ $screening->result_level }}
                </h2>
                <p class="text-xs text-gray-400 mt-2">{{ $screening->created_at->format('d M Y') }}</p>
            </div>

            <!-- Data Pasien -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 flex-grow">
                <h3 class="text-sm font-bold text-[#001B48] mb-4 flex items-center border-b border-gray-100 pb-2">
                    <i data-lucide="user" class="w-4 h-4 mr-2"></i> Pasien
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Nama</p>
                        <p class="font-bold text-[#001B48] text-sm truncate">{{ $screening->client_name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                            <span class="block text-xs text-gray-400 uppercase font-bold mb-1">Umur</span>
                            <span class="block text-base font-bold text-[#001B48]">{{ $screening->snapshot_age }} Thn</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Jenis Kelamin</p>
                            <p class="font-bold text-[#001B48] text-sm">{{ ($profile->gender ?? '-') == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-xs text-gray-400 uppercase">BMI</p>
                            <p class="font-bold text-[#001B48] text-sm">{{ $bmi }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Tensi</p>
                            <p class="font-bold text-[#001B48] text-sm truncate">{{ $tensi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM 2: ANALISIS & REKOMENDASI (5 Kolom Grid - Terlebar) -->
        <div class="lg:col-span-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col h-full overflow-hidden">
            <h3 class="text-base font-bold text-[#001B48] mb-4 flex items-center flex-shrink-0 border-b border-gray-100 pb-3">
                <i data-lucide="file-text" class="w-5 h-5 mr-2 text-[#E3943B]"></i> Analisis & Rekomendasi
            </h3>
            
            <div class="overflow-y-auto custom-scrollbar pr-2 space-y-6">
                <!-- Keterangan Medis (Umum) -->
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Kesimpulan Medis</h4>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-gray-700 text-sm leading-relaxed text-justify">
                            {{ $riskLevel ? $riskLevel->description : 'Tidak ada keterangan tersedia.' }}
                        </p>
                    </div>
                </div>

                <!-- Arahan Umum (Dari Risk Level) -->
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Arahan Tindakan</h4>
                    <div class="text-gray-800 text-sm leading-relaxed text-justify font-medium">
                        {!! $riskLevel ? nl2br(e($riskLevel->suggestion)) : 'Tidak ada arahan tersedia.' !!}
                    </div>
                </div>

                <!-- REKOMENDASI PERSONAL (Dinamis Berdasarkan Faktor yang Dipilih) -->
                <div class="mt-4">
                    <h4 class="text-xs font-bold text-[#001B48] uppercase mb-3 flex items-center">
                        <i data-lucide="check-circle-2" class="w-4 h-4 mr-2 text-green-600"></i>
                        Langkah Perbaikan Personal
                    </h4>
                    
                    @if ($screening->details->isEmpty())
                        <div class="text-center py-4 bg-green-50 rounded-lg border border-green-100 text-green-700 text-sm">
                            <p>Gaya hidup Anda sudah sangat baik! Pertahankan.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($screening->details as $detail)
                                @if($detail->riskFactor->recommendation)
                                    <div class="bg-blue-50 p-4 rounded-xl border-l-4 border-[#E3943B]">
                                        <h5 class="text-xs font-bold text-[#001B48] uppercase mb-1 opacity-80">
                                            Solusi untuk: {{ Str::limit($detail->riskFactor->name, 40) }}
                                        </h5>
                                        <p class="text-gray-800 text-sm leading-relaxed">
                                            {{ $detail->riskFactor->recommendation }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- KOLOM 3: FAKTOR RISIKO (3 Kolom Grid) -->
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-200 p-5 flex flex-col h-full overflow-hidden">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3 flex-shrink-0">
                <h3 class="text-sm font-bold text-[#001B48] flex items-center">
                    <i data-lucide="clipboard-list" class="w-4 h-4 mr-2 text-gray-400"></i> Faktor Risiko
                </h3>
                <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($screening->details) }}</span>
            </div>
            
            <div class="overflow-y-auto custom-scrollbar pr-1">
                @if ($screening->details->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-center text-gray-400 py-8">
                        <i data-lucide="shield-check" class="w-10 h-10 mb-2 text-green-200"></i>
                        <p class="text-xs">Tidak ditemukan faktor risiko signifikan.</p>
                    </div>
                @else
                    <ul class="space-y-2">
                        @foreach ($screening->details as $d)
                        <li class="p-3 rounded-lg bg-gray-50 border border-gray-100 flex items-start" x-data="{ openExplanation: false }">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-red-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <div class="flex-grow">
                                <span class="text-[10px] font-bold text-[#E3943B] block mb-0.5">[{{ $d->riskFactor->code }}]</span>
                                <p class="text-xs font-bold text-gray-800 leading-tight">{{ $d->riskFactor->name }}</p>

                                @if ($d->riskFactor->medical_explanation)
                                <button @click="openExplanation = !openExplanation" class="mt-2 text-xs text-[#E3943B] hover:text-[#001B48] font-semibold flex items-center">
                                    <span x-text="openExplanation ? 'Sembunyikan Penjelasan' : 'Lihat Penjelasan Medis'"></span>
                                    <i data-lucide="chevron-down" class="w-3 h-3 ml-1 transform transition-transform duration-200" :class="{ 'rotate-180': openExplanation }"></i>
                                </button>
                                <div x-show="openExplanation" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="mt-2 p-3 bg-white rounded-lg border border-gray-100">
                                    <p class="text-xs text-gray-700 leading-relaxed">{{ $d->riskFactor->medical_explanation }}</p>
                                </div>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
</div>

<style>
    /* Scrollbar Halus */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }

    @media print {
        @page { margin: 2cm; size: A4 portrait; }
        body { background-color: white !important; color: black !important; font-family: serif; font-size: 12pt; }
        
        /* Sembunyikan elemen non-cetak */
        .no-print, nav, footer, .bg-gray-50, .overflow-hidden { display: none !important; }
        
        /* Reset Layout Container */
        .h-\[calc\(100vh-5rem\)\] { height: auto !important; padding: 0 !important; background: white !important; }
        .overflow-y-auto, .custom-scrollbar { overflow: visible !important; height: auto !important; }
        .grid { display: block !important; }
        .lg\:col-span-3, .lg\:col-span-6, .lg\:col-span-12 { width: 100% !important; display: block !important; }
        
        /* Styling Kartu menjadi Section Laporan */
        .bg-white, .rounded-2xl, .shadow-sm, .border { 
            background: white !important; 
            box-shadow: none !important; 
            border: none !important; 
            border-radius: 0 !important; 
            padding: 0 !important; 
            margin-bottom: 20px !important; 
        }

        /* Header Laporan (Logo & Judul) - Kita buat element khusus print */
        .print-header { display: block !important; text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .print-header h1 { font-size: 24pt; margin: 0; color: #000; }
        .print-header p { font-size: 10pt; color: #555; }

        /* Styling Konten Spesifik */
        h1, h2, h3, h4 { color: #000 !important; page-break-after: avoid; }
        p, li { color: #000 !important; line-height: 1.5; }
        
        /* Section Borders */
        .section-print { border: 1px solid #ccc !important; padding: 15px !important; margin-bottom: 20px !important; border-radius: 5px !important; }
    }
</style>

<!-- Header Khusus Print (Tersembunyi di Layar) -->
<div class="print-header hidden">
    <h1>Laporan Hasil Skrining Hipertensi</h1>
    <p>TensiTrack - Sistem Pakar Deteksi Dini Hipertensi</p>
</div>

<script>
    // Ensure icons are rendered if Lucide is loaded dynamically
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

@endsection