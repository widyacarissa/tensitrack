@extends('layouts.admin')

@section('title', 'Riwayat Skrining')

@section('content')

<div class="flex flex-col h-full">
    
    <!-- Header + Search + Export -->
    <div class="bg-white p-4 border-b border-gray-100 sticky top-0 z-10 space-y-3">
        <div class="flex justify-between items-center">
            <h1 class="font-bold text-[#001B48] text-lg">Riwayat</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.history.export') }}" class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center shadow-sm hover:bg-green-200 transition">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                </a>
                <a href="{{ route('admin.history.print') }}" target="_blank" class="w-8 h-8 bg-[#001B48] text-white rounded-full flex items-center justify-center shadow-sm hover:bg-blue-900 transition">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
        
        <div class="flex gap-2">
            <form action="{{ route('admin.history.index') }}" method="GET" class="relative flex-grow">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama..." 
                       class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#001B48]/20">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
            </form>
            
            <!-- Simple Filter Trigger (could be expanded later) -->
            <div class="relative">
                <select onchange="window.location.href=this.value" class="h-full pl-3 pr-8 py-2 bg-gray-50 border-none rounded-xl text-xs font-bold text-gray-600 focus:ring-0">
                    <option value="{{ route('admin.history.index') }}">Semua</option>
                    <option value="{{ route('admin.history.index', ['filter_risk' => 'Tinggi']) }}" {{ request('filter_risk') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="{{ route('admin.history.index', ['filter_risk' => 'Sedang']) }}" {{ request('filter_risk') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="{{ route('admin.history.index', ['filter_risk' => 'Rendah']) }}" {{ request('filter_risk') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Content List -->
    <div class="flex-grow overflow-y-auto p-4 space-y-3">
        @forelse($screenings as $s)
        @php
            $isHigh = stripos($s->result_level, 'tinggi') !== false;
            $isMed = stripos($s->result_level, 'sedang') !== false;
            $borderColor = $isHigh ? 'border-red-500' : ($isMed ? 'border-yellow-500' : 'border-green-500');
            $bgColor = $isHigh ? 'bg-red-50' : ($isMed ? 'bg-yellow-50' : 'bg-green-50');
            $textColor = $isHigh ? 'text-red-700' : ($isMed ? 'text-yellow-700' : 'text-green-700');
        @endphp

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden border-l-[6px] {{ $borderColor }}">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="font-bold text-[#001B48] text-sm">{{ $s->client_name }}</h3>
                    <div class="flex items-center text-xs text-gray-400 mt-0.5">
                        <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                        {{ $s->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                <span class="px-2 py-1 rounded-lg text-[10px] font-bold {{ $bgColor }} {{ $textColor }}">
                    {{ $s->result_level }}
                </span>
            </div>

            <div class="flex justify-between items-center pt-3 mt-2 border-t border-gray-50">
                <span class="text-xs text-gray-500">Skor: <b>{{ $s->score }}</b> Faktor</span>
                
                <div class="flex gap-2">
                    <a href="{{ route('admin.history.show', $s->id) }}" class="px-3 py-1.5 bg-[#001B48] text-white rounded-lg text-xs font-bold shadow-sm hover:bg-blue-900 transition flex items-center">
                        Detail <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                    </a>
                    <form action="{{ route('admin.history.destroy', $s->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="file-clock" class="w-8 h-8 text-gray-400"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Belum ada riwayat skrining.</p>
        </div>
        @endforelse
        
        <!-- Pagination -->
        <div class="pt-4 pb-20">
            {{ $screenings->onEachSide(0)->links('pagination::simple-tailwind') }}
        </div>
    </div>

</div>

@endsection