@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<div class="flex flex-col gap-4">

    <!-- 1. Ringkasan (Scroll Horizontal) -->
    <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar snap-x">
        <!-- Users -->
        <div class="min-w-[140px] bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between snap-start">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center mb-3">
                <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-[#001B48]">{{ $totalUsers }}</span>
                <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">Total Pengguna</p>
            </div>
        </div>

        <!-- Screenings -->
        <div class="min-w-[140px] bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between snap-start">
            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center mb-3">
                <i data-lucide="file-check" class="w-4 h-4 text-green-600"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-[#001B48]">{{ $totalScreenings }}</span>
                <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">Total Skrining</p>
            </div>
        </div>

        <!-- Factors -->
        <div class="min-w-[140px] bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between snap-start">
            <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center mb-3">
                <i data-lucide="activity" class="w-4 h-4 text-orange-600"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-[#001B48]">{{ $totalRiskFactors }}</span>
                <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">Faktor Risiko</p>
            </div>
        </div>
    </div>

    <!-- 2. Distribusi Risiko -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-[#001B48] text-sm">Distribusi Risiko</h3>
            <i data-lucide="pie-chart" class="w-4 h-4 text-gray-400"></i>
        </div>

        <div class="space-y-3">
            @foreach (['Tinggi', 'Sedang', 'Rendah'] as $level)
                @php
                    $percentage = $riskPercentages[$level];
                    $color = $level == 'Tinggi' ? 'bg-red-500' : ($level == 'Sedang' ? 'bg-yellow-500' : 'bg-green-500');
                    $text = $level == 'Tinggi' ? 'text-red-600' : ($level == 'Sedang' ? 'text-yellow-600' : 'text-green-600');
                    $hex = $level == 'Tinggi' ? '#ef4444' : ($level == 'Sedang' ? '#eab308' : '#22c55e');
                @endphp
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="{{ $text }}">{{ $level }}</span>
                        <span class="text-gray-600">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="{{ $color }} h-full rounded-full" style="width: {{ $percentage }}%; background-color: {{ $hex }};"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 3. Aktivitas Terbaru -->
    <div>
        <div class="flex justify-between items-center mb-3 px-1">
            <h3 class="font-bold text-[#001B48] text-sm">Aktivitas Terbaru</h3>
            <a href="{{ route('admin.history.index') }}" class="text-xs font-bold text-[#E3943B]">Lihat Semua</a>
        </div>

        @if ($latestScreenings->isEmpty())
            <div class="text-center py-8 bg-white rounded-2xl border border-gray-100 border-dashed">
                <p class="text-xs text-gray-400 italic">Belum ada data skrining masuk.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($latestScreenings as $s)
                @php
                    $isHigh = stripos($s->result_level, 'tinggi') !== false;
                    $isMed = stripos($s->result_level, 'sedang') !== false;
                    $borderColor = $isHigh ? 'border-red-500' : ($isMed ? 'border-yellow-500' : 'border-green-500');
                    $bgColor = $isHigh ? 'bg-red-50' : ($isMed ? 'bg-yellow-50' : 'bg-green-50');
                    $textColor = $isHigh ? 'text-red-700' : ($isMed ? 'text-yellow-700' : 'text-green-700');
                @endphp

                <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 {{ $borderColor }} flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-400 font-bold mb-0.5">{{ $s->created_at->format('d M, H:i') }}</p>
                        <h4 class="font-bold text-[#001B48] text-sm">{{ $s->client_name }}</h4>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $bgColor }} {{ $textColor }}">
                        {{ $s->result_level }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection