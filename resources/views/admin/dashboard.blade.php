@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<div class="flex flex-col h-full overflow-hidden">
    <!-- Header -->
    <div class="flex-shrink-0 mb-4">
        <h1 class="text-2xl font-bold tracking-tight text-[#001B48]">Dashboard</h1>
        <p class="text-xs text-gray-500">Ringkasan performa sistem.</p>
    </div>

    <!-- Baris 1: Statistik Ringkas (4 Kolom) -->
    <div class="flex-shrink-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Total Pengguna -->
                <div class="p-3 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-medium text-gray-500 uppercase">Total Pengguna</p>
                        <p class="text-xl font-bold text-[#001B48]">{{ $totalUsers }}</p>
                    </div>
                    <div class="p-1.5 bg-blue-50 rounded-lg">
                        <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>

        <!-- Faktor Risiko -->
        <div class="p-3 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-medium text-gray-500 uppercase">Faktor Risiko</p>
                <p class="text-xl font-bold text-[#001B48]">{{ $totalRiskFactors }}</p>
            </div>
            <div class="p-1.5 bg-orange-50 rounded-lg">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-[#E3943B]"></i>
            </div>
        </div>

        <!-- Tingkat Risiko -->
        <div class="p-3 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-medium text-gray-500 uppercase">Tingkat Risiko</p>
                <p class="text-xl font-bold text-[#001B48]">{{ $totalRiskLevels }}</p>
            </div>
            <div class="p-1.5 bg-red-50 rounded-lg">
                <i data-lucide="bar-chart-2" class="w-5 h-5 text-red-600"></i>
            </div>
        </div>

        <!-- Total Aturan -->
        <div class="p-3 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-medium text-gray-500 uppercase">Total Aturan</p>
                <p class="text-xl font-bold text-[#001B48]">{{ $totalRules }}</p>
            </div>
            <div class="p-1.5 bg-green-50 rounded-lg">
                <i data-lucide="git-branch" class="w-5 h-5 text-green-600"></i>
            </div>
        </div>
    </div>

    <!-- Baris 2: Grid Utama (Distribusi & Aktivitas) -->
    <div class="flex-grow grid grid-cols-1 lg:grid-cols-12 gap-4 min-h-0">
        
        <!-- Kolom Kiri: Distribusi Risiko (40%) -->
        <div class="lg:col-span-5 bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex flex-col h-full overflow-hidden">
            <h3 class="text-sm font-bold text-[#001B48] mb-4 flex-shrink-0">Distribusi Risiko</h3>
            
            <div class="space-y-4 flex-grow flex flex-col justify-center overflow-y-auto">
                @foreach (['Tinggi', 'Sedang', 'Rendah', 'Tidak Berisiko'] as $level)
                    @php
                        $percentage = $riskPercentages[$level] ?? 0;
                        $colorClass = match($level) {
                            'Tinggi' => 'bg-red-500',
                            'Sedang' => 'bg-orange-500',
                            'Rendah' => 'bg-blue-500',
                            'Tidak Berisiko' => 'bg-green-500',
                            default => 'bg-gray-500'
                        };
                        $hexColor = match($level) {
                            'Tinggi' => '#ef4444',
                            'Sedang' => '#f97316',
                            'Rendah' => '#3b82f6',
                            'Tidak Berisiko' => '#22c55e',
                            default => '#6b7280'
                        };
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-700">{{ $level }}</span>
                            <span class="text-xs font-bold text-gray-900">{{ $percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-xl h-16 overflow-hidden">
                            <div class="{{ $colorClass }} h-16 rounded-xl transition-all duration-1000 ease-out" style="width: {{ $percentage }}%; background-color: {{ $hexColor }};"></div>
                        </div>
                    </div>
                @endforeach
                
                @if ($totalScreenings == 0)
                    <p class="text-center text-xs text-gray-400 italic mt-4">Belum ada data.</p>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Aktivitas Terbaru (60%) -->
        <div class="lg:col-span-7 bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col h-full overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center flex-shrink-0">
                <h3 class="text-sm font-bold text-[#001B48]">Aktivitas Terbaru</h3>
                <a href="{{ route('admin.history.index') }}" class="text-xs text-[#E3943B] hover:underline font-medium">Lihat Semua</a>
            </div>
            
            <div class="flex-grow overflow-y-auto p-0">
                @if ($latestScreenings->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 p-8">
                        <i data-lucide="clipboard-x" class="w-8 h-8 mb-2 opacity-50"></i>
                        <span class="text-xs italic">Belum ada riwayat skrining.</span>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($latestScreenings as $screening)
                            <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full bg-[#001B48]/10 text-[#001B48] flex items-center justify-center text-sm font-bold border border-[#001B48]/20">
                                        {{ strtoupper(substr($screening->client_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-[#001B48]">{{ $screening->client_name }}</p>
                                        <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                            <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                            {{ $screening->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </div>
                                
                                @php
                                    $result = strtolower($screening->result_level);
                                    
                                    if (str_contains($result, 'tinggi') || str_contains($result, 'berat')) {
                                        $badgeClass = 'bg-red-50 text-red-700 border border-red-100';
                                    } elseif (str_contains($result, 'sedang')) {
                                        $badgeClass = 'bg-orange-50 text-orange-700 border border-orange-100';
                                    } elseif (str_contains($result, 'rendah') || str_contains($result, 'ringan')) {
                                        $badgeClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                                    } else {
                                        $badgeClass = 'bg-green-50 text-green-700 border border-green-100';
                                    }
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                    {{ $screening->result_level }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection