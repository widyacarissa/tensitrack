@extends('layouts.admin')

@section('title', 'Tambah Aturan Diagnosa')

@section('content')
<div class="w-full animate-fade-up">
    <!-- Header Page -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-blue">Tambah Aturan Diagnosa</h2>
            <p class="text-sm text-gray-500">Buat logika baru untuk sistem pakar deteksi hipertensi.</p>
        </div>
        <a href="{{ route('admin.rules.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.rules.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <!-- PANEL KIRI: KONFIGURASI DASAR -->
            <div class="xl:col-span-1 space-y-6">
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="settings" class="w-5 h-5 text-brand-orange"></i>
                            Konfigurasi Dasar
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- Kode -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kode Aturan</label>
                            <input type="text" value="{{ $newCode }}" readonly class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 font-mono text-sm cursor-not-allowed">
                        </div>

                        <!-- Prioritas -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Prioritas (1 = Tertinggi)</label>
                            <input type="number" name="priority" value="1" class="w-full rounded-lg border-gray-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm text-sm font-bold" min="1">
                        </div>

                        <!-- Risk Level -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tingkat Risiko (Output)</label>
                            <select name="risk_level_id" required class="w-full rounded-lg border-gray-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm text-sm">
                                <option value="">-- Pilih Tingkat Risiko --</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }} ({{ $level->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL KANAN: LOGIKA DIAGNOSA -->
            <div class="xl:col-span-2 space-y-6">
                
                <!-- KONDISI FAKTOR UTAMA -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="git-branch" class="w-5 h-5 text-brand-blue"></i>
                            Kondisi Faktor Utama (Wajib)
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Tipe Logika -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pengecekan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ operator: 'AND' }">
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none transition-all" :class="operator === 'AND' ? 'border-brand-blue ring-1 ring-brand-blue' : 'border-gray-200'">
                                    <input type="radio" name="operator" value="AND" class="sr-only" @click="operator = 'AND'" checked>
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-bold text-gray-900">WAJIB SEMUA (AND)</span>
                                            <span class="mt-1 flex items-center text-xs text-gray-500">User harus memiliki semua faktor yang dipilih.</span>
                                        </span>
                                    </span>
                                    <i data-lucide="check-circle" class="h-5 w-5 text-brand-blue" x-show="operator === 'AND'"></i>
                                </label>
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none transition-all" :class="operator === 'OR' ? 'border-brand-orange ring-1 ring-brand-orange' : 'border-gray-200'">
                                    <input type="radio" name="operator" value="OR" class="sr-only" @click="operator = 'OR'">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-bold text-gray-900">SALAH SATU (OR)</span>
                                            <span class="mt-1 flex items-center text-xs text-gray-500">User cukup memiliki salah satu faktor.</span>
                                        </span>
                                    </span>
                                    <i data-lucide="check-circle" class="h-5 w-5 text-brand-orange" x-show="operator === 'OR'"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Multi Select Chips -->
                        <div class="relative mt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Faktor Risiko Wajib (Dinamis)</label>
                            
                            <select id="hiddenSelect" name="risk_factor_ids[]" multiple class="hidden">
                                @foreach($factors as $factor)
                                    <option value="{{ $factor->id }}">
                                        {{ $factor->code }} - {{ $factor->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="w-full min-h-[60px] p-3 rounded-xl border-2 border-dashed border-gray-300 focus-within:border-brand-blue bg-gray-50/50 flex flex-wrap gap-2 cursor-text transition-colors" onclick="document.getElementById('searchInput').focus()">
                                <div id="chipsContainer" class="flex flex-wrap gap-2"></div>
                                <input type="text" id="searchInput" placeholder="Klik untuk mencari faktor risiko apapun..." class="flex-grow min-w-[200px] text-sm border-none focus:ring-0 p-1 bg-transparent text-gray-700">
                            </div>

                            <div id="dropdownList" class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl max-h-72 overflow-y-auto hidden">
                                @foreach($factors as $factor)
                                    <div class="dropdown-item px-5 py-4 hover:bg-brand-blue hover:text-white cursor-pointer group border-b border-gray-50 last:border-b-0 transition-colors" 
                                         data-id="{{ $factor->id }}" 
                                         data-text="{{ $factor->code }} - {{ $factor->name }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-brand-blue group-hover:text-white">{{ $factor->code }}</span>
                                                <span class="text-xs text-gray-500 group-hover:text-blue-100">{{ $factor->name }}</span>
                                            </div>
                                            <i data-lucide="plus" class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SYARAT TAMBAHAN -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="hash" class="w-5 h-5 text-brand-orange"></i>
                            Jumlah Faktor Risiko Tambahan
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Minimal Jumlah</label>
                                <div class="relative">
                                    <input type="number" name="min_other_factors" value="0" class="w-full pl-4 pr-12 py-2.5 rounded-lg border-gray-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm font-bold text-gray-700 text-lg" min="0">
                                    <span class="absolute right-4 top-3 text-xs text-gray-400 font-medium">Faktor</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Maksimal Jumlah</label>
                                <div class="relative">
                                    <input type="number" name="max_other_factors" value="99" class="w-full pl-4 pr-12 py-2.5 rounded-lg border-gray-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm font-bold text-gray-700 text-lg" min="0">
                                    <span class="absolute right-4 top-3 text-xs text-gray-400 font-medium">Faktor</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM ACTIONS -->
                <div class="flex justify-end items-center gap-4 bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <button type="submit" class="px-10 py-3 bg-brand-blue text-white rounded-lg font-bold hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20 flex items-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i> Simpan Aturan Baru
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const hiddenSelect = document.getElementById('hiddenSelect');
    const chipsContainer = document.getElementById('chipsContainer');
    const searchInput = document.getElementById('searchInput');
    const dropdownList = document.getElementById('dropdownList');
    const dropdownItems = document.querySelectorAll('.dropdown-item');

    // Init Render
    refreshChips();

    // Show Dropdown on Focus
    searchInput.addEventListener('focus', () => {
        dropdownList.classList.remove('hidden');
        filterDropdown();
    });

    // Hide Dropdown when clicking outside
    document.addEventListener('click', (e) => {
        const isClickInside = searchInput.contains(e.target) || dropdownList.contains(e.target) || chipsContainer.contains(e.target);
        if (!isClickInside) {
            dropdownList.classList.add('hidden');
        }
    });

    // Filter Logic
    searchInput.addEventListener('input', filterDropdown);

    function filterDropdown() {
        const query = searchInput.value.toLowerCase();
        let hasVisible = false;

        dropdownItems.forEach(item => {
            const text = item.dataset.text.toLowerCase();
            const id = item.dataset.id;
            const isSelected = Array.from(hiddenSelect.options).find(opt => opt.value == id && opt.selected);
            
            if (text.includes(query) && !isSelected) {
                item.classList.remove('hidden');
                hasVisible = true;
            } else {
                item.classList.add('hidden');
            }
        });

        // Optional: Show empty state if needed
    }

    // Click Item Handler
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default anchor/div behavior
            e.stopPropagation(); // Prevent bubbling closing the dropdown immediately

            const id = this.dataset.id;
            const option = Array.from(hiddenSelect.options).find(opt => opt.value == id);
            
            if (option) {
                option.selected = true;
                refreshChips();
                searchInput.value = '';
                searchInput.focus(); // Keep focus to keep dropdown open
                filterDropdown(); // Re-filter to hide selected item
            }
        });
    });

    function refreshChips() {
        chipsContainer.innerHTML = '';
        const selectedOptions = Array.from(hiddenSelect.options).filter(opt => opt.selected);

        selectedOptions.forEach(opt => {
            const code = opt.text.split('-')[0].trim();
            
            const chip = document.createElement('div');
            chip.className = 'flex items-center bg-brand-blue text-white text-xs font-bold rounded-full pl-3 pr-1 py-1 gap-2 shadow-sm animate-fade-up';
            chip.innerHTML = `
                <span>${code}</span>
                <button type="button" class="hover:bg-white/20 rounded-full p-0.5 transition-colors focus:outline-none" data-id="${opt.value}">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </button>
            `;
            
            // Remove Handler
            chip.querySelector('button').addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                opt.selected = false;
                refreshChips();
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });

            chipsContainer.appendChild(chip);
        });

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
});
</script>
@endsection