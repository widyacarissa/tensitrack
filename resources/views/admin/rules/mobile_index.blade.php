@extends('layouts.admin')

@section('title', 'Aturan Diagnosa')

@section('content')

<div class="flex flex-col h-full">
    
    <!-- Header + Search -->
    <div class="bg-white p-4 border-b border-gray-100 sticky top-0 z-10">
        <div class="flex justify-between items-center mb-4">
            <h1 class="font-bold text-[#001B48] text-lg">Aturan Diagnosa</h1>
            <a href="{{ route('admin.rules.create') }}" class="w-8 h-8 bg-[#001B48] rounded-full flex items-center justify-center text-white shadow-md hover:bg-blue-900 transition">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </a>
        </div>
        
        <form action="{{ route('admin.rules.index') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode aturan..." 
                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#001B48]/20">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 transform -translate-y-1/2"></i>
        </form>
    </div>

    <!-- Content List -->
    <div class="flex-grow overflow-y-auto p-4 space-y-3">
        @forelse($rules as $rule)
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm relative">
            <!-- Header Card: Kode & Prioritas -->
            <div class="flex justify-between items-start mb-3 border-b border-gray-50 pb-3">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Kode Aturan</span>
                    <h3 class="font-extrabold text-[#E3943B] text-lg">{{ $rule->code }}</h3>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Prioritas</span>
                    <div class="flex justify-end mt-1">
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-bold">#{{ $rule->priority }}</span>
                    </div>
                </div>
            </div>

            <!-- Logic Visualization -->
            <div class="space-y-3 text-sm">
                <!-- IF (Conditions) -->
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-2 flex items-center">
                        <i data-lucide="git-merge" class="w-3 h-3 mr-1"></i> Kondisi (IF)
                    </p>
                    
                    @if($rule->requiredFactor)
                    <div class="flex items-start mb-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <span class="block text-xs font-bold text-gray-700">Wajib: {{ $rule->requiredFactor->name }}</span>
                            <span class="text-[10px] text-gray-400">({{ $rule->requiredFactor->code }})</span>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <span class="block text-xs font-medium text-gray-600">
                                Faktor Tambahan: <b class="text-[#001B48]">{{ $rule->min_other_factors }}</b> s/d <b class="text-[#001B48]">{{ $rule->max_other_factors >= 99 ? 'Tak Terbatas' : $rule->max_other_factors }}</b>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- THEN (Result) -->
                <div class="relative">
                    <div class="absolute left-1/2 -top-3 -translate-x-1/2 w-6 h-6 bg-white rounded-full border border-gray-200 flex items-center justify-center z-10">
                        <i data-lucide="arrow-down" class="w-3 h-3 text-gray-400"></i>
                    </div>
                    
                    @php
                        $isHigh = stripos($rule->riskLevel->name, 'tinggi') !== false;
                        $isMed = stripos($rule->riskLevel->name, 'sedang') !== false;
                        $bgClass = $isHigh ? 'bg-red-50 border-red-100' : ($isMed ? 'bg-yellow-50 border-yellow-100' : 'bg-green-50 border-green-100');
                        $textClass = $isHigh ? 'text-red-700' : ($isMed ? 'text-yellow-700' : 'text-green-700');
                    @endphp

                    <div class="{{ $bgClass }} border p-3 rounded-xl text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Hasil Diagnosa (THEN)</p>
                        <p class="{{ $textClass }} font-extrabold">{{ $rule->riskLevel->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-4 pt-3 border-t border-gray-50">
                <a href="{{ route('admin.rules.edit', $rule->id) }}" class="text-gray-400 hover:text-[#E3943B] transition p-2">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                </a>
                                            <form action="{{ route('admin.rules.destroy', $rule->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="git-branch" class="w-8 h-8 text-gray-400"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Belum ada aturan diagnosa.</p>
        </div>
        @endforelse
        
        <!-- Pagination -->
        <div class="pt-4 pb-20">
            {{ $rules->onEachSide(0)->links('pagination::simple-tailwind') }}
        </div>
    </div>

</div>

@endsection