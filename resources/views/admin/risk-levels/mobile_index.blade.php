@extends('layouts.admin')

@section('title', 'Tingkat Risiko')

@section('content')

<div class="flex flex-col h-full">
    
    <!-- Header + Search -->
    <div class="bg-white p-4 border-b border-gray-100 sticky top-0 z-10">
        <div class="flex justify-between items-center mb-4">
            <h1 class="font-bold text-[#001B48] text-lg">Tingkat Risiko</h1>
            <a href="{{ route('admin.risk-levels.create') }}" class="w-8 h-8 bg-[#001B48] rounded-full flex items-center justify-center text-white shadow-md hover:bg-blue-900 transition">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </a>
        </div>
        
        <form action="{{ route('admin.risk-levels.index') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari..." 
                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#001B48]/20">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 transform -translate-y-1/2"></i>
        </form>
    </div>

    <!-- Content List -->
    <div class="flex-grow overflow-y-auto p-4 space-y-3">
        @forelse($riskLevels as $item)
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
            <!-- Label Kode -->
            <div class="absolute top-0 right-0 bg-[#E3943B] text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl">
                {{ $item->code }}
            </div>

            <h3 class="font-bold text-[#001B48] text-base pr-8 mb-1">{{ $item->name }}</h3>
            <p class="text-xs text-gray-500 line-clamp-2 mb-3 leading-relaxed">{{ $item->description }}</p>

            <div class="flex justify-between items-center pt-3 border-t border-gray-50">
                <div class="flex gap-2">
                    <a href="{{ route('admin.risk-levels.edit', $item->id) }}" class="text-xs font-bold text-[#E3943B] flex items-center px-3 py-1.5 bg-orange-50 rounded-lg">
                        <i data-lucide="pencil" class="w-3 h-3 mr-1.5"></i> Edit
                    </a>
                </div>
                                    <form action="{{ route('admin.risk-levels.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-red-500 transition">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Tidak ada data.</p>
        </div>
        @endforelse
        
        <!-- Pagination -->
        <div class="pt-4">
            {{ $riskLevels->onEachSide(0)->links('pagination::simple-tailwind') }}
        </div>
    </div>

</div>

@endsection