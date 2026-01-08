@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Banner -->
    <div class="bg-[#001B48] rounded-2xl p-8 text-white mb-8 relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1/3 bg-[#E3943B] opacity-10 skew-x-12 transform origin-bottom-left"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Halo, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-blue-100">Selamat datang kembali di TensiTrack. Mari pantau kesehatan Anda hari ini.</p>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
        <!-- Admin Dashboard Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Pengguna</h3>
                    <div class="p-2 bg-blue-50 rounded-lg text-[#001B48]">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-[#001B48]">12</p>
                <p class="text-xs text-green-500 mt-2 flex items-center">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +2 minggu ini
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Skrining</h3>
                    <div class="p-2 bg-orange-50 rounded-lg text-[#E3943B]">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-[#001B48]">45</p>
                <p class="text-xs text-green-500 mt-2 flex items-center">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +10 minggu ini
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Risiko Tinggi</h3>
                    <div class="p-2 bg-red-50 rounded-lg text-red-500">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-[#001B48]">3</p>
                <p class="text-xs text-gray-400 mt-2">Perlu perhatian</p>
            </div>
        </div>
    @else
        <!-- Client Dashboard Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card Skrining -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 col-span-1 md:col-span-2">
                <h3 class="font-bold text-[#001B48] mb-4">Mulai Skrining Baru</h3>
                <p class="text-gray-600 text-sm mb-6">Lakukan pengecekan risiko hipertensi secara mandiri. Hanya butuh waktu kurang dari 5 menit.</p>
                <button class="bg-[#E3943B] text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-600 transition shadow-md inline-flex items-center">
                    <i data-lucide="play-circle" class="w-5 h-5 mr-2"></i>
                    Mulai Sekarang
                </button>
            </div>

            <!-- Card Riwayat Terakhir -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-[#001B48] mb-4">Hasil Terakhir</h3>
                <div class="text-center py-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-3">
                        <i data-lucide="help-circle" class="w-8 h-8"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Belum ada data skrining.</p>
                </div>
            </div>
        </div>
    @endif
@endsection