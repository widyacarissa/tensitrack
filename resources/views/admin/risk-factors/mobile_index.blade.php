@extends('layouts.admin')

@section('title', 'Faktor Risiko')

@section('content')

<div class="flex flex-col h-full">
    
    <!-- Header + Search -->
    <div class="bg-white p-4 border-b border-gray-100 sticky top-0 z-10">
        <div class="flex justify-between items-center mb-4">
            <h1 class="font-bold text-[#001B48] text-lg">Faktor Risiko</h1>
            <div class="flex">
                <a href="{{ route('admin.risk-factors.create') }}" class="w-8 h-8 bg-[#001B48] rounded-full flex items-center justify-center text-white shadow-md hover:bg-blue-900 transition">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.risk-factors.index') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama..." 
                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#001B48]/20">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 transform -translate-y-1/2"></i>
        </form>
    </div>

    <!-- Content List -->
    <div class="flex-grow overflow-y-auto p-4 space-y-3">
        @forelse($riskFactors as $item)
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden" x-data="{ expanded: false }">
            <!-- Label Kode -->
            <div class="absolute top-0 right-0 bg-[#001B48] text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl shadow-sm">
                {{ $item->code }}
            </div>

            <h3 class="font-bold text-[#001B48] text-base pr-8 mb-2">{{ $item->name }}</h3>
            
            <!-- Pertanyaan (Always Visible) -->
            <div class="bg-blue-50 p-3 rounded-xl mb-3">
                <p class="text-[10px] font-bold text-blue-800 uppercase mb-1">Pertanyaan Skrining</p>
                <p class="text-xs text-blue-900 italic">"{{ $item->question_text }}"</p>
            </div>

            <!-- Expandable Details -->
            <div x-show="expanded" x-collapse style="display: none;">
                <div class="space-y-3 mb-3 pt-2">
                    @if($item->medical_explanation)
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Penjelasan Medis</p>
                        <p class="text-xs text-gray-600 leading-relaxed text-justify">{{ $item->medical_explanation }}</p>
                    </div>
                    @endif
                    
                    @if($item->recommendation)
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Solusi / Saran</p>
                        <p class="text-xs text-gray-600 leading-relaxed text-justify">{{ $item->recommendation }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-center pt-2 border-t border-gray-50 mt-2">
                <!-- Toggle Detail Button -->
                <button @click="expanded = !expanded" class="text-xs font-bold text-gray-500 flex items-center hover:text-[#001B48] transition">
                    <span x-text="expanded ? 'Tutup Detail' : 'Lihat Detail'"></span>
                    <i data-lucide="chevron-down" class="w-3 h-3 ml-1 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                </button>

                <div class="flex gap-3">
                    <a href="{{ route('admin.risk-factors.edit', $item->id) }}" class="text-gray-400 hover:text-[#E3943B] transition">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </a>
                    <form action="{{ route('admin.risk-factors.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="clipboard-x" class="w-8 h-8 text-gray-400"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Tidak ada data faktor risiko.</p>
        </div>
        @endforelse
        
        <!-- Pagination -->
        <div class="pt-4 pb-20"> <!-- Extra padding for bottom nav -->
            {{ $riskFactors->onEachSide(0)->links('pagination::simple-tailwind') }}
        </div>
    </div>

</div>

@endsection