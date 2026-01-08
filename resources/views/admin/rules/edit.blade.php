@extends('layouts.admin')

@section('title', 'Edit Aturan Diagnosa')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        <form action="{{ route('admin.rules.update', $rule->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Kode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode</label>
                    <input type="text" value="{{ $rule->code }}" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm p-2 font-mono">
                </div>

                <!-- Prioritas -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">Prioritas Pengecekan</label>
                    <input type="number" name="priority" id="priority" value="{{ $rule->priority }}" required class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2" min="1">
                    <p class="mt-1 text-xs text-gray-500">Angka lebih kecil (misal: 1) berarti dicek lebih awal.</p>
                </div>

                <!-- Risk Level -->
                <div>
                    <label for="risk_level_id" class="block text-sm font-medium text-gray-700">Hasil Diagnosa (Tingkat Risiko)</label>
                    <select name="risk_level_id" id="risk_level_id" required class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#001B48] focus:ring-[#001B48] p-2">
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ $rule->risk_level_id == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Logika Operator -->
                <div x-data="{ operator: '{{ $rule->operator }}' }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Logika Faktor Wajib</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="operator" value="AND" @click="operator = 'AND'" class="h-4 w-4 text-[#001B48] border-gray-300 focus:ring-[#001B48]" {{ $rule->operator == 'AND' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Wajib Semua</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="operator" value="OR" @click="operator = 'OR'" class="h-4 w-4 text-orange-500 border-gray-300 focus:ring-orange-500" {{ $rule->operator == 'OR' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Salah Satu</span>
                        </label>
                    </div>
                    <p class="mt-2 text-xs" :class="operator === 'AND' ? 'text-blue-600' : 'text-orange-600'">
                        <span x-show="operator === 'AND'">* User harus memiliki <strong>semua</strong> faktor di bawah ini.</span>
                        <span x-show="operator === 'OR'">* User cukup memiliki <strong>salah satu</strong> faktor di bawah ini.</span>
                    </p>
                </div>

                <!-- Daftar Faktor Wajib (Multi Select Simple) -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Daftar Faktor Risiko Wajib</label>
                    
                    <select id="hiddenSelect" name="risk_factor_ids[]" multiple class="hidden">
                        @foreach($factors as $factor)
                            <option value="{{ $factor->id }}" {{ $rule->riskFactors->contains($factor->id) ? 'selected' : '' }}>{{ $factor->code }} - {{ $factor->name }}</option>
                        @endforeach
                    </select>

                    <div id="multiSelectContainer" class="min-h-[42px] p-2 border border-gray-300 rounded-md bg-white flex flex-wrap gap-2 items-center cursor-text" onclick="document.getElementById('searchInput').focus()">
                        <div id="chipsContainer" class="flex flex-wrap gap-2"></div>
                        <input type="text" id="searchInput" placeholder="Tambah faktor..." class="flex-grow min-w-[150px] border-none focus:ring-0 p-1 text-sm">
                    </div>

                    <!-- Dropdown List -->
                    <div id="dropdownList" class="absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden z-50">
                        @foreach($factors as $factor)
                            <div class="dropdown-item px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm border-b border-gray-50 last:border-b-0" data-id="{{ $factor->id }}" data-text="{{ $factor->code }} {{ $factor->name }}">
                                <span class="font-bold">{{ $factor->code }}</span> - {{ $factor->name }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Faktor Risiko Tambahan (Rentang)</label>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase mb-1 block">Minimal</label>
                            <input type="number" name="min_other_factors" value="{{ $rule->min_other_factors }}" class="w-full rounded-md border border-gray-300 p-2 text-sm focus:border-[#001B48] focus:ring-[#001B48] shadow-sm" min="0">
                        </div>
                        <div class="mt-4 text-gray-400 font-bold">s/d</div>
                        <div class="flex-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase mb-1 block">Maksimal</label>
                            <input type="number" name="max_other_factors" value="{{ $rule->max_other_factors }}" class="w-full rounded-md border border-gray-300 p-2 text-sm focus:border-[#001B48] focus:ring-[#001B48] shadow-sm" min="0">
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 italic">* Menghitung jumlah faktor risiko lain di luar yang dipilih di atas.</p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-6">
                    <a href="{{ route('admin.rules.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#001B48] hover:bg-blue-900 transition">Perbarui Aturan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hiddenSelect = document.getElementById('hiddenSelect');
    const chipsContainer = document.getElementById('chipsContainer');
    const searchInput = document.getElementById('searchInput');
    const dropdownList = document.getElementById('dropdownList');
    const dropdownItems = document.querySelectorAll('.dropdown-item');

    function refreshChips() {
        chipsContainer.innerHTML = '';
        const selectedOptions = Array.from(hiddenSelect.options).filter(opt => opt.selected);
        selectedOptions.forEach(opt => {
            const textParts = opt.text.split('-');
            const code = textParts[0].trim();
            const name = textParts[1] ? textParts[1].trim() : '';

            const chip = document.createElement('div');
            chip.className = 'flex items-center bg-gray-100 text-gray-800 border border-gray-300 text-xs px-2 py-1 rounded-md gap-2 max-w-full';
            chip.innerHTML = `
                <span class="font-bold flex-shrink-0">${code}</span>
                <span class="truncate opacity-75 italic">${name}</span>
                <button type="button" class="text-gray-400 hover:text-red-500 flex-shrink-0" data-id="${opt.value}">&times;</button>
            `;
            chip.querySelector('button').addEventListener('click', (e) => {
                e.stopPropagation(); opt.selected = false; refreshChips(); filterDropdown();
            });
            chipsContainer.appendChild(chip);
        });
    }

    refreshChips();

    searchInput.addEventListener('focus', () => { dropdownList.classList.remove('hidden'); filterDropdown(); });
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdownList.contains(e.target)) dropdownList.classList.add('hidden');
    });

    searchInput.addEventListener('input', filterDropdown);

    function filterDropdown() {
        const query = searchInput.value.toLowerCase();
        dropdownItems.forEach(item => {
            const isSelected = Array.from(hiddenSelect.options).find(opt => opt.value == item.dataset.id && opt.selected);
            if (item.dataset.text.toLowerCase().includes(query) && !isSelected) item.classList.remove('hidden');
            else item.classList.add('hidden');
        });
    }

    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const option = Array.from(hiddenSelect.options).find(opt => opt.value == this.dataset.id);
            if (option) {
                option.selected = true;
                refreshChips();
                searchInput.value = ''; searchInput.focus();
                filterDropdown();
            }
        });
    });
});
</script>
@endsection