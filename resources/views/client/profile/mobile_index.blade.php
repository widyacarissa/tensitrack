@extends('layouts.mobile')

@section('title', 'Profil Saya')

@section('header-action')
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="text-gray-400 hover:text-red-500">
        <i data-lucide="log-out" class="w-6 h-6"></i>
    </button>
</form>
@endsection

@section('content')

@php
    $redirectFromScreening = request('redirect_from') === 'screening';
@endphp

<div class="pb-24 flex flex-col h-full bg-gray-50" x-init="setTimeout(() => lucide.createIcons(), 100)">

    <!-- 1. HERO SECTION (Mirip Desktop Header) -->
    <div class="bg-[#001B48] px-6 pt-6 pb-12 rounded-b-[3rem] shadow-lg relative overflow-hidden" style="background-color: #001B48;">
        <!-- Background Pattern/Decor -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-[#E3943B]/10 rounded-full -ml-10 -mb-10 blur-2xl"></div>

        <div class="relative z-10 flex flex-col items-center text-center text-white">
            <!-- Avatar -->
            <div class="w-24 h-24 rounded-full border-4 border-white/20 bg-white text-[#001B48] flex items-center justify-center text-3xl font-bold mb-3 shadow-2xl">
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover rounded-full">
                @else
                    {{ substr(auth()->user()->name, 0, 1) }}
                @endif
            </div>
            
            <h1 class="text-xl font-bold tracking-wide">{{ auth()->user()->name }}</h1>
            <p class="text-blue-200 text-xs font-light">{{ auth()->user()->email }}</p>
        </div>
    </div>

    <!-- 2. FLOATING STATS (Overlapping) -->
    <div class="px-4 -mt-8 relative z-20">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 grid grid-cols-3 gap-4 divide-x divide-gray-100">
            <!-- BMI -->
            <div class="flex flex-col items-center text-center">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">BMI</span>
                <span class="text-xl font-extrabold text-[#001B48]">{{ $bmi }}</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full {{ $bmi_category == 'Normal' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} font-bold mt-1">
                    {{ $bmi_category }}
                </span>
            </div>
            <!-- Umur -->
            <div class="flex flex-col items-center text-center">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Umur</span>
                <span class="text-xl font-extrabold text-[#001B48]">{{ $profile->age ?? '-' }}</span>
                <span class="text-[10px] text-gray-400 font-medium mt-1">Tahun</span>
            </div>
            <!-- Tensi -->
            <div class="flex flex-col items-center text-center">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tensi</span>
                <span class="text-xl font-extrabold text-[#E3943B] truncate max-w-full">{{ $latest_result }}</span>
                <span class="text-[10px] text-gray-400 font-medium mt-1">mmHg</span>
            </div>
        </div>
    </div>

    <!-- 3. ALERT (If redirected) -->
    @if ($redirectFromScreening)
        <div class="mx-4 mt-6 p-4 bg-orange-50 border-l-4 border-[#E3943B] rounded-r-xl shadow-sm animate-pulse flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-[#E3943B] mr-3 mt-0.5 flex-shrink-0"></i>
            <div>
                <h3 class="font-bold text-[#001B48] text-xs uppercase tracking-wide mb-1">Data Belum Lengkap</h3>
                <p class="text-xs text-gray-700 leading-relaxed">Lengkapi data bertanda (<span class="text-red-500 font-bold">*</span>) untuk lanjut.</p>
            </div>
        </div>
    @endif

    <!-- 4. TABS & CONTENT -->
    <div class="flex-grow px-4 mt-6" x-data="{ tab: 'data' }">
        
        <!-- Custom Tab Navigation -->
        <div class="flex p-1 bg-gray-200/60 rounded-xl mb-6 relative">
            <button @click="tab = 'data'" 
                    class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all duration-300 z-10 relative flex items-center justify-center"
                    :class="tab === 'data' ? 'text-[#001B48] shadow-sm bg-white' : 'text-gray-500 hover:text-gray-700'">
                <i data-lucide="user-cog" class="w-4 h-4 mr-2"></i> Data Diri
            </button>
            <button @click="tab = 'history'" 
                    class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all duration-300 z-10 relative flex items-center justify-center"
                    :class="tab === 'history' ? 'text-[#001B48] shadow-sm bg-white' : 'text-gray-500 hover:text-gray-700'">
                <i data-lucide="history" class="w-4 h-4 mr-2"></i> Riwayat
            </button>
        </div>

        <!-- TAB: DATA DIRI -->
        <div x-show="tab === 'data'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-init="$watch('tab', value => { if(value === 'data') setTimeout(() => lucide.createIcons(), 50) })">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header Form (Like Desktop) -->
                <div class="bg-[#001B48] px-5 py-4 flex items-center justify-between relative overflow-hidden" style="background-color: #001B48;">
                    <!-- Decor -->
                    <div class="absolute top-0 right-0 w-16 h-16 bg-white/5 rounded-full -mr-8 -mt-8"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-sm font-bold text-white">Data Diri</h3>
                        <p class="text-blue-200 text-[10px]">Pastikan data selalu valid.</p>
                    </div>
                    <div class="relative z-10 w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white">
                        <i data-lucide="user" class="w-4 h-4 text-white"></i>
                    </div>
                </div>

                <form action="{{ route('client.profile.update') }}" method="post" class="p-5 space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Lengkap <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i data-lucide="type" class="w-4 h-4"></i></span>
                            <input type="text" name="full_name" value="{{ old('full_name', $profile->full_name ?? auth()->user()->name) }}" 
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 pl-10 py-3 text-sm font-semibold text-[#001B48] focus:border-[#E3943B] focus:ring-[#E3943B] focus:bg-white transition-all" required placeholder="Nama Lengkap">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Umur (Thn) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i data-lucide="calendar" class="w-4 h-4"></i></span>
                                <input type="number" name="age" value="{{ old('age', $profile->age) }}" 
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 pl-10 py-3 text-sm font-semibold text-[#001B48] focus:border-[#E3943B] focus:ring-[#E3943B] focus:bg-white transition-all" required>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Gender <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i data-lucide="users" class="w-4 h-4"></i></span>
                                <select name="gender" class="w-full rounded-xl border-gray-200 bg-gray-50 pl-10 py-3 text-sm font-semibold text-[#001B48] focus:border-[#E3943B] focus:ring-[#E3943B] focus:bg-white transition-all appearance-none">
                                    <option value="L" {{ old('gender', $profile->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', $profile->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 pointer-events-none"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tinggi (cm)</label>
                            <input type="number" name="height" value="{{ old('height', $profile->height) }}" 
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-3 text-sm font-semibold text-[#001B48] focus:border-[#E3943B] focus:ring-[#E3943B] focus:bg-white transition-all text-center" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Berat (kg)</label>
                            <input type="number" name="weight" value="{{ old('weight', $profile->weight) }}" 
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-3 text-sm font-semibold text-[#001B48] focus:border-[#E3943B] focus:ring-[#E3943B] focus:bg-white transition-all text-center" required>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                         <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block flex justify-between">
                             <span>Tensi Terakhir (mmHg)</span>
                             <span class="text-gray-300 font-normal normal-case italic">Opsional</span>
                         </label>
                         <div class="flex gap-3 items-center">
                             <div class="relative flex-1">
                                <input type="number" name="systolic" placeholder="SYS" value="{{ old('systolic', $profile->systolic) }}" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 text-sm font-bold text-[#001B48] focus:border-[#E3943B] focus:ring-[#E3943B] text-center focus:bg-white placeholder-gray-300">
                             </div>
                             <span class="text-gray-300 font-light text-xl">/</span>
                             <div class="relative flex-1">
                                <input type="number" name="diastolic" placeholder="DIA" value="{{ old('diastolic', $profile->diastolic) }}" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 text-sm font-bold text-[#001B48] focus:border-[#E3943B] focus:ring-[#E3943B] text-center focus:bg-white placeholder-gray-300">
                             </div>
                         </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 bg-[#E3943B] text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 hover:bg-orange-600 transition active:scale-95 flex items-center justify-center group">
                            <i data-lucide="save" class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB: RIWAYAT -->
        <div x-show="tab === 'history'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            @if($history->isEmpty())
                <div class="flex flex-col items-center justify-center text-center py-12 bg-white rounded-2xl border border-gray-100 border-dashed">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-300"></i>
                    </div>
                    <p class="text-sm font-bold text-gray-800">Belum ada data riwayat</p>
                    <p class="text-xs text-gray-400 mt-1 max-w-[200px]">Lakukan skrining pertama Anda untuk melihat hasil di sini.</p>
                    <a href="{{ route('client.screening.index') }}" class="mt-6 px-6 py-2.5 rounded-full bg-[#001B48] text-white text-xs font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition">
                        Mulai Skrining
                    </a>
                </div>
            @else
                <div class="flex justify-between items-center mb-4 px-1">
                    <h3 class="text-sm font-bold text-[#001B48]">Daftar Pemeriksaan</h3>
                    <span class="bg-[#001B48] text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $history->count() }}</span>
                </div>

                <div class="space-y-4">
                    @foreach($history as $h)
                    @php
                        $r = strtolower($h->result_level);
                        $isTinggi = stripos($r, 'tinggi') !== false;
                        $isSedang = stripos($r, 'sedang') !== false;
                        $isRendah = stripos($r, 'rendah') !== false;

                        if ($isTinggi) {
                            $borderColor = 'border-red-500'; $textColor = 'text-red-600'; $bgColor = 'bg-red-50';
                        } elseif ($isSedang) {
                            $borderColor = 'border-orange-500'; $textColor = 'text-orange-600'; $bgColor = 'bg-orange-50';
                        } elseif ($isRendah) {
                            $borderColor = 'border-blue-500'; $textColor = 'text-blue-600'; $bgColor = 'bg-blue-50';
                        } else {
                            $borderColor = 'border-green-500'; $textColor = 'text-green-600'; $bgColor = 'bg-green-50';
                        }
                    @endphp

                    <a href="{{ route('client.profile.detail', $h->id) }}" class="block bg-white rounded-2xl shadow-sm hover:shadow-md transition group relative overflow-hidden border-l-[6px] {{ $borderColor }}">
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <div class="flex items-center mb-1">
                                    <i data-lucide="calendar" class="w-3 h-3 text-gray-400 mr-1.5"></i>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ $h->created_at->format('d M Y') }}</span>
                                </div>
                                <h4 class="font-extrabold text-[#001B48] text-base mb-1">{{ $h->result_level }}</h4>
                                <p class="text-xs text-gray-500">Skor Risiko: <span class="font-bold text-[#001B48]">{{ $h->score }}</span> Faktor</p>
                            </div>
                            <div class="w-10 h-10 rounded-full {{ $bgColor }} flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i data-lucide="chevron-right" class="w-5 h-5 {{ $textColor }}"></i>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: 'Data Berhasil Disimpan',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                popup: 'text-sm font-bold rounded-xl shadow-xl m-4'
            }
        });
    @endif
</script>

@endsection