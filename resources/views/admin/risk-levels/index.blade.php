@extends('layouts.admin')

@section('title', 'Tingkat Risiko')

@section('content')

<!-- Header Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 mb-4 md:mb-6">
    <h1 class="text-2xl font-bold text-[#001B48]">Tingkat Risiko</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola data tingkat risiko aplikasi.</p>
</div>

<!-- Table Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Toolbar: Actions & Search (Sekarang di dalam box) -->
        <div class="p-4 md:p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            
            <!-- Kiri: Tombol -->
            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ route('admin.risk-levels.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#001B48] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition shadow-sm leading-none">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Data</span>
                </a>
                <a href="{{ route('admin.risk-levels.print') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-[#001B48] text-sm font-medium rounded-lg hover:bg-[#001B48] hover:text-white hover:border-[#001B48] transition shadow-sm leading-none">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                    <span>Cetak PDF</span>
                </a>
            </div>

            <!-- Kanan: Search -->
            <div class="w-full md:w-auto">
                <form action="{{ route('admin.risk-levels.index') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari data..." 
                           class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#001B48] focus:border-transparent text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Saran</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($riskLevels as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-[#E3943B] text-center">
                            {{ $item->code }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                            {{ $item->name }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500 max-w-xs truncate text-center">
                            {{ $item->description }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500 max-w-xs truncate text-center">
                            {{ $item->suggestion }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.risk-levels.edit', $item->id) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-[#E3943B] text-white rounded-md hover:bg-orange-600 transition leading-none shadow-sm">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    <span class="text-xs">Edit</span>
                                </a>
                                <form action="{{ route('admin.risk-levels.destroy', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition leading-none shadow-sm">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span class="text-xs">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 md:p-6 border-t border-gray-200">
            {{ $riskLevels->links() }}
        </div>
    </div>

@endsection
