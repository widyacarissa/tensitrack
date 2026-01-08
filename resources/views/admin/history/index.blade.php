@extends('layouts.admin')

@section('title', 'Laporan Riwayat')

@section('content')

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 mb-4 md:mb-6">
        <h1 class="text-2xl font-bold text-[#001B48]">Laporan Riwayat</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau hasil skrining dan diagnosa pengguna.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 md:p-6 border-b border-gray-100">
            
            <form action="{{ route('admin.history.index') }}" method="GET" class="flex flex-col lg:flex-row justify-between items-center gap-4">
                
                <!-- Kiri: Filter -->
                <div class="flex items-center gap-2 w-full lg:w-auto mr-auto">
                    <div class="relative">
                        <select name="filter_risk" onchange="this.form.submit()" class="appearance-none pl-3 pr-8 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#001B48] text-gray-600 bg-white cursor-pointer">
                            <option value="">Semua Risiko</option>
                            <option value="Tidak Berisiko" {{ request('filter_risk') == 'Tidak Berisiko' ? 'selected' : '' }}>Tidak Berisiko</option>
                            <option value="Rendah" {{ request('filter_risk') == 'Rendah' ? 'selected' : '' }}>Risiko Rendah</option>
                            <option value="Sedang" {{ request('filter_risk') == 'Sedang' ? 'selected' : '' }}>Risiko Sedang</option>
                            <option value="Tinggi" {{ request('filter_risk') == 'Tinggi' ? 'selected' : '' }}>Risiko Tinggi</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Search & Print -->
                <div class="flex items-center gap-2 w-full lg:w-auto">
                    <div class="relative w-full lg:w-64">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#001B48] focus:border-transparent text-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                        </div>
                    </div>

                    <a href="{{ route('admin.history.export', request()->query()) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-lg hover:bg-green-700 transition shadow-sm whitespace-nowrap">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                        <span>Export</span>
                    </a>

                    <a href="{{ route('admin.history.print', request()->query()) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#001B48] text-white text-sm font-bold rounded-lg hover:bg-blue-900 transition shadow-sm whitespace-nowrap">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        <span>Cetak</span>
                    </a>
                </div>

            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-4 py-4 font-semibold w-16 text-center">No</th>
                        <th class="px-4 py-4 font-semibold w-32 text-center">Tanggal</th>
                        <th class="px-4 py-4 font-semibold text-center">Nama Client</th>
                        <th class="px-4 py-4 font-semibold text-center w-16">Umur</th>
                        <th class="px-4 py-4 font-semibold text-center w-16">BMI</th>
                        <th class="px-4 py-4 font-semibold text-center w-24">Tensi</th>
                        <th class="px-4 py-4 font-semibold text-center">Hasil Risiko</th>
                        <th class="px-4 py-4 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($screenings as $screening)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 text-sm text-gray-600 text-center">{{ ($screenings->currentPage() - 1) * $screenings->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap text-center">{{ $screening->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-4 text-sm font-bold text-[#001B48] text-center">{{ $screening->client_name }}</td>
                        
                        <!-- Umur -->
                        <td class="px-4 py-4 text-sm text-gray-600 text-center">{{ $screening->snapshot_age }} Thn</td>
                        
                        <!-- BMI Calculation -->
                        @php
                            $bmi = 0;
                            if ($screening->snapshot_height && $screening->snapshot_weight) {
                                $h_m = $screening->snapshot_height / 100;
                                $bmi = round($screening->snapshot_weight / ($h_m * $h_m), 1);
                            }
                        @endphp
                        <td class="px-4 py-4 text-sm text-gray-600 text-center font-medium">{{ $bmi }}</td>

                        <!-- Tensi -->
                        <td class="px-4 py-4 text-sm text-gray-600 text-center font-medium whitespace-nowrap">
                            {{ $screening->snapshot_systolic }}/{{ $screening->snapshot_diastolic }} mmHg
                        </td>

                        <td class="px-4 py-4 text-sm text-center">
                            @php
                                $r = strtolower($screening->result_level);
                                $badgeClass = 'bg-green-100 text-green-700 border border-green-200';
                                
                                if (stripos($r, 'tinggi') !== false) {
                                    $badgeClass = 'bg-red-100 text-red-700 border border-red-200';
                                } elseif (stripos($r, 'sedang') !== false) {
                                    $badgeClass = 'bg-orange-50 text-[#E3943B] border border-orange-300';
                                } elseif (stripos($r, 'rendah') !== false) {
                                    $badgeClass = 'bg-blue-100 text-blue-700 border border-blue-200';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                {{ $screening->result_level }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center space-x-2 whitespace-nowrap">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.history.show', $screening->id) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-[#001B48] text-white rounded-md hover:bg-blue-900 transition leading-none shadow-sm">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span class="text-xs">Detail</span>
                                </a>
                                <form action="{{ route('admin.history.destroy', $screening->id) }}" method="POST" class="inline-block">
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
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="p-4 md:p-6 border-t border-gray-100">
            {{ $screenings->links() }}
        </div>
    </div>
@endsection