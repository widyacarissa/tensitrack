@extends('layouts.mobile')

@section('title', 'Hasil Analisa')

@section('header-action')
    <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#001B48]">
        <i data-lucide="x" class="w-6 h-6"></i>
    </a>
@endsection

@section('content')

@php 
    $level = strtolower($screening->result_level);
    
    $isTinggi = stripos($level, 'tinggi') !== false;
    $isSedang = stripos($level, 'sedang') !== false;
    $isRendah = stripos($level, 'rendah') !== false;

    // Tentukan Warna & Ikon
    if ($isTinggi) {
        $colorClass = 'bg-red-500 shadow-red-200';
        $textClass = 'text-red-600';
        $iconName = 'alert-triangle';
    } elseif ($isSedang) {
        $colorClass = 'bg-orange-500 shadow-orange-200';
        $textClass = 'text-orange-600';
        $iconName = 'alert-circle';
    } elseif ($isRendah) {
        $colorClass = 'bg-blue-500 shadow-blue-200';
        $textClass = 'text-blue-600';
        $iconName = 'info';
    } else {
        $colorClass = 'bg-green-500 shadow-green-200';
        $textClass = 'text-green-600';
        $iconName = 'shield-check';
    }
@endphp

<div class="flex flex-col min-h-full bg-gray-50 pb-24"> <!-- pb-24 for fixed bottom action -->

    <!-- 1. RESULT CARD (Top) -->
    <div class="bg-white p-6 pb-8 rounded-b-[2.5rem] shadow-sm relative z-10">
        <div class="flex flex-col items-center text-center">
            
            <!-- Icon Ring -->
            <div class="relative mb-4">
                <div class="w-20 h-20 rounded-full flex items-center justify-center shadow-lg mx-auto {{ $colorClass }}">
                    <i data-lucide="{{ $iconName }}" class="w-10 h-10 text-white"></i>
                </div>
            </div>

            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tingkat Risiko Anda</p>
            <h1 class="text-2xl font-extrabold leading-tight mb-2 {{ $textClass }}">{{ $screening->result_level }}</h1>
            <p class="text-xs text-gray-400">{{ $screening->created_at->format('d M Y • H:i') }} WIB</p>
        
            <!-- Vital Stats Row -->
            <div class="grid grid-cols-3 gap-3 w-full mt-8">
                <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">BMI</p>
                    <p class="text-lg font-bold text-[#001B48]">{{ $bmi }}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Umur</p>
                    <p class="text-lg font-bold text-[#001B48]">{{ $screening->snapshot_age }}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Tensi</p>
                    <p class="text-lg font-bold text-[#001B48] truncate">{{ $tensi }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. ANALYSIS & ADVICE -->
    <div class="px-5 mt-6 space-y-6">
        
        <!-- Medical Conclusion -->
        <div>
            <h3 class="text-sm font-bold text-[#001B48] mb-3 flex items-center">
                <i data-lucide="stethoscope" class="w-4 h-4 mr-2 text-[#E3943B]"></i> Analisis Medis
            </h3>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-sm text-gray-700 leading-relaxed">
                {{ $riskLevel ? $riskLevel->description : 'Tidak ada keterangan.' }}
            </div>
        </div>

        <!-- Action Plan -->
        <div>
            <h3 class="text-sm font-bold text-[#001B48] mb-3 flex items-center">
                <i data-lucide="list-checks" class="w-4 h-4 mr-2 text-[#E3943B]"></i> Saran Tindakan
            </h3>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-sm text-gray-700 leading-relaxed space-y-2">
                    {!! $riskLevel ? nl2br(e($riskLevel->suggestion)) : '-' !!}
                </div>
            </div>
        </div>

        <!-- Personal Factors Accordion -->
        <div>
            <h3 class="text-sm font-bold text-[#001B48] mb-3 flex items-center justify-between">
                <div class="flex items-center">
                    <i data-lucide="activity" class="w-4 h-4 mr-2 text-[#E3943B]"></i> Faktor Risiko Terdeteksi
                </div>
                <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ count($screening->details) }}</span>
            </h3>

            @if($screening->details->isEmpty())
                <div class="bg-green-50 p-4 rounded-2xl border border-green-100 text-center">
                    <p class="text-sm text-green-700 font-medium">Tidak ada faktor risiko signifikan.</p>
                </div>
            @else
                <div class="space-y-3" x-data="{ active: null }">
                    @foreach($screening->details as $index => $detail)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                        <button @click="active === {{ $index }} ? active = null : active = {{ $index }}" class="w-full flex items-center justify-between p-4 text-left">
                            <div class="flex items-center overflow-hidden">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-red-500 mr-3 flex-shrink-0"></i>
                                <div>
                                    <span class="text-[10px] font-bold text-[#E3943B] block mb-0.5">[{{ $detail->riskFactor->code }}]</span>
                                    <p class="text-sm font-bold text-[#001B48] leading-tight truncate pr-2">{{ $detail->riskFactor->name }}</p>
                                </div>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0" :class="{'rotate-180': active === {{ $index }}}"></i>
                        </button>
                        
                        <div x-show="active === {{ $index }}" x-collapse class="bg-gray-50 px-4 pb-4 pt-2 border-t border-gray-100">
                            @if($detail->riskFactor->recommendation)
                                <div class="mb-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Rekomendasi</p>
                                    <p class="text-xs text-gray-700 leading-relaxed">{{ $detail->riskFactor->recommendation }}</p>
                                </div>
                            @endif
                            @if($detail->riskFactor->medical_explanation)
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Penjelasan Medis</p>
                                    <p class="text-xs text-gray-500 italic leading-relaxed">{{ $detail->riskFactor->medical_explanation }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Fixed Bottom Actions -->
<div class="fixed bottom-0 left-0 w-full bg-white p-4 border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-40 flex gap-3">
    <a href="{{ route('client.pdf.print', ['id' => $screening->id, 'action' => 'download']) }}" class="flex-1 py-3.5 bg-white border border-gray-200 text-[#001B48] font-bold rounded-xl text-sm flex items-center justify-center hover:bg-gray-50 transition active:bg-gray-100">
        <i data-lucide="download" class="w-4 h-4 mr-2"></i> Simpan PDF
    </a>
    <a href="{{ route('home') }}" class="flex-1 py-3.5 bg-[#001B48] text-white font-bold rounded-xl text-sm flex items-center justify-center shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition active:scale-95">
        Selesai
    </a>
</div>

@endsection