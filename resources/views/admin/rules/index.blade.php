@extends('layouts.admin')

@section('title', 'Manajemen Aturan')

@section('content')

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 mb-4 md:mb-6">
        <h1 class="text-2xl font-bold text-[#001B48]">Manajemen Aturan</h1>
        <p class="text-sm text-gray-500 mt-1">Tentukan logika diagnosa penyakit.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Toolbar: Actions & Search -->
        <div class="p-4 md:p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            
            <!-- Kiri: Tombol -->
            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ route('admin.rules.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#001B48] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition shadow-sm leading-none">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Aturan</span>
                </a>
                <a href="{{ route('admin.rules.print') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-[#001B48] text-sm font-medium rounded-lg hover:bg-[#001B48] hover:text-white hover:border-[#001B48] transition shadow-sm leading-none">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                    <span>Cetak PDF</span>
                </a>
            </div>

            <!-- Kanan: Search -->
            <div class="w-full md:w-auto">
                <form action="{{ route('admin.rules.index') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode..." 
                           class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#001B48] focus:border-transparent text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Prio</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Syarat Faktor Utama</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Syarat Faktor Lain</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Hasil</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($rules as $rule)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 text-sm text-gray-500 font-mono text-center">{{ $rule->priority }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-[#E3943B] text-center">{{ $rule->code }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 text-center">
                            @if($rule->riskFactors->count() > 0)
                                <div class="flex flex-wrap justify-center gap-1">
                                    @foreach($rule->riskFactors as $rf)
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded border border-blue-100">
                                            {{ $rf->code }}
                                        </span>
                                    @endforeach
                                </div>
                                <div class="mt-1 text-[10px] text-gray-400 font-medium uppercase tracking-wide">
                                    {{ $rule->operator == 'OR' ? '(Salah Satu)' : '(Wajib Semua)' }}
                                </div>
                            @else
                                <span class="text-gray-400 italic">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 text-center">
                            {{ $rule->min_other_factors }} s/d {{ $rule->max_other_factors == 99 ? 'Tak Terbatas' : $rule->max_other_factors }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 text-center">
                            @php
                                $riskName = strtolower($rule->riskLevel->name);
                                $colorClass = 'bg-green-100 text-green-800 border border-green-200'; // Default: Hijau

                                if (str_contains($riskName, 'tinggi')) {
                                    $colorClass = 'bg-red-100 text-red-800 border border-red-200';
                                } elseif (str_contains($riskName, 'sedang')) {
                                    $colorClass = 'bg-orange-100 text-orange-800 border border-orange-200';
                                } elseif (str_contains($riskName, 'rendah')) {
                                    $colorClass = 'bg-blue-100 text-blue-800 border border-blue-200';
                                }
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $colorClass }}">
                                {{ $rule->riskLevel->name }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.rules.edit', $rule->id) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-[#E3943B] text-white rounded-md hover:bg-orange-600 transition leading-none shadow-sm">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    <span class="text-xs">Edit</span>
                                </a>
                                <form action="{{ route('admin.rules.destroy', $rule->id) }}" method="POST" class="inline-block">
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
                    <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada aturan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 md:p-6 border-t border-gray-100">
            {{ $rules->links() }}
        </div>
    </div>
@endsection