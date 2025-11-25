@extends('layouts.admin.app')

@section('title', 'Tambah Aturan Baru')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>Tambah Aturan Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.beranda') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.rules.index') }}">Manajemen Aturan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Aturan Baru</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card p-3 shadow-sm mb-4">
        <form action="{{ route('admin.rules.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Aturan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="tingkat_risiko_id" class="form-label">Tingkat Risiko Hasil <span class="text-danger">*</span></label>
                <select class="form-select @error('tingkat_risiko_id') is-invalid @enderror" id="tingkat_risiko_id" name="tingkat_risiko_id" required>
                    <option value="">Pilih Tingkat Risiko</option>
                    @foreach($tingkatRisikos as $tr)
                        <option value="{{ $tr->id }}" {{ old('tingkat_risiko_id') == $tr->id ? 'selected' : '' }}>{{ $tr->kode }} - {{ $tr->tingkat_risiko }}</option>
                    @endforeach
                </select>
                @error('tingkat_risiko_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" value="{{ old('priority', 10) }}" min="0" required>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <h4>Kondisi Aturan <span class="text-danger">*</span></h4>
            <div class="alert alert-info">
                Aturan akan terpenuhi jika <strong>salah satu</strong> dari "Grup Kondisi" di bawah ini bernilai benar. <br>
                Sebuah "Grup Kondisi" akan bernilai benar jika <strong>semua</strong> "Kondisi" di dalamnya terpenuhi.
            </div>
            <div id="conditionGroupsContainer">
                <!-- Condition Groups will be added here by JavaScript -->
            </div>
            <button type="button" id="addConditionGroup" class="btn btn-info mt-3"><i class="fas fa-plus"></i> Tambah Grup Kondisi (OR)</button>
            <hr>

            <button type="submit" class="btn btn-primary">Simpan Aturan</button>
            <a href="{{ route('admin.rules.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let groupIndex = {{ old('condition_groups') ? count(old('condition_groups')) : 0 }};
            const conditionGroupsContainer = document.getElementById('conditionGroupsContainer');
            const addConditionGroupBtn = document.getElementById('addConditionGroup');
            const faktorRisikos = @json($faktorRisikos); // Pass FaktorRisikos to JS

            function addConditionGroup(initialConditions = []) {
                const newGroupHtml = `
                    <div class="card border-primary p-3 mb-3 condition-group" data-group-index="${groupIndex}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-primary">Grup Kondisi #${groupIndex + 1} (AND)</h5>
                            <button type="button" class="btn btn-danger btn-sm remove-condition-group"><i class="fas fa-trash"></i> Hapus Grup</button>
                        </div>
                        <div class="conditions-container">
                            <!-- Conditions will be added here -->
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-3 add-condition"><i class="fas fa-plus"></i> Tambah Kondisi (AND)</button>
                    </div>
                `;
                conditionGroupsContainer.insertAdjacentHTML('beforeend', newGroupHtml);

                const currentGroup = conditionGroupsContainer.lastElementChild;
                const conditionsContainer = currentGroup.querySelector('.conditions-container');
                const addConditionBtn = currentGroup.querySelector('.add-condition');
                
                let conditionIndex = 0;
                
                function addCondition(initialData = {}) {
                    const newConditionHtml = `
                        <div class="row mb-3 pb-3 border-bottom condition-item" data-condition-index="${conditionIndex}">
                            <div class="col-md-3">
                                <label class="form-label">Tipe Kondisi</label>
                                <select name="condition_groups[${groupIndex}][conditions][${conditionIndex}][type]" class="form-select condition-type" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="HAS_FAKTOR">Memiliki Faktor</option>
                                    <option value="FAKTOR_LAIN_COUNT">Jumlah Faktor Lain (selain E01)</option>
                                    <option value="FAKTOR_TOTAL_COUNT">Jumlah Total Faktor</option>
                                    <option value="GAYA_HIDUP_COUNT">Jumlah Faktor Gaya Hidup</option>
                                </select>
                                <small class="form-text text-muted condition-type-helper"></small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Faktor Risiko</label>
                                <select name="condition_groups[${groupIndex}][conditions][${conditionIndex}][faktor_risiko_id]" class="form-select faktor-risiko-select" style="display: none;">
                                    <option value="">Pilih Faktor</option>
                                    ${faktorRisikos.map(fr => `<option value="${fr.id}">${fr.kode} - ${fr.name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Operator</label>
                                <select name="condition_groups[${groupIndex}][conditions][${conditionIndex}][operator]" class="form-select operator-select" required>
                                    <option value="==">==</option>
                                    <option value="!=">!=</option>
                                    <option value=">=">>=</option>
                                    <option value="<="><=</option>
                                    <option value=">">></option>
                                    <option value="<"><</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nilai</label>
                                <input type="number" name="condition_groups[${groupIndex}][conditions][${conditionIndex}][value]" class="form-control condition-value" value="0" required>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="button" class="btn btn-danger btn-sm remove-condition"><i class="fas fa-minus"></i> Hapus</button>
                            </div>
                        </div>
                    `;
                    conditionsContainer.insertAdjacentHTML('beforeend', newConditionHtml);
                    const newConditionItem = conditionsContainer.lastElementChild;

                    // Set initial data if available (for old() values)
                    newConditionItem.querySelector('.condition-type').value = initialData.type || '';
                    newConditionItem.querySelector('.faktor-risiko-select').value = initialData.faktor_risiko_id || '';
                    newConditionItem.querySelector('.operator-select').value = initialData.operator || '==';
                    newConditionItem.querySelector('.condition-value').value = initialData.value !== undefined ? initialData.value : 0;

                    // Handle visibility and helper text based on condition type
                    const typeSelect = newConditionItem.querySelector('.condition-type');
                    const faktorRisikoSelect = newConditionItem.querySelector('.faktor-risiko-select');
                    const valueInput = newConditionItem.querySelector('.condition-value');
                    const operatorSelect = newConditionItem.querySelector('.operator-select');
                    const helperText = newConditionItem.querySelector('.condition-type-helper');

                    const typeExplanations = {
                        'HAS_FAKTOR': 'Cek jika faktor spesifik ada (Nilai 1) atau tidak ada (Nilai 0).',
                        'FAKTOR_LAIN_COUNT': 'Hitung jumlah faktor selain E01.',
                        'FAKTOR_TOTAL_COUNT': 'Hitung semua faktor yang dipilih.',
                        'GAYA_HIDUP_COUNT': 'Hitung jumlah faktor tipe "Gaya Hidup".'
                    };

                    function toggleFaktorRisikoSelect() {
                        helperText.textContent = typeExplanations[typeSelect.value] || '';
                        if (typeSelect.value === 'HAS_FAKTOR') {
                            faktorRisikoSelect.style.display = 'block';
                            faktorRisikoSelect.setAttribute('required', 'required');
                            operatorSelect.value = '==';
                            valueInput.value = initialData.value !== undefined ? initialData.value : 1;
                            valueInput.setAttribute('min', '0');
                            valueInput.setAttribute('max', '1');
                        } else {
                            faktorRisikoSelect.style.display = 'none';
                            faktorRisikoSelect.removeAttribute('required');
                            faktorRisikoSelect.value = '';
                            operatorSelect.value = initialData.operator || '>=';
                            valueInput.value = initialData.value !== undefined ? initialData.value : 0;
                            valueInput.removeAttribute('min');
                            valueInput.removeAttribute('max');
                        }
                    }
                    typeSelect.addEventListener('change', toggleFaktorRisikoSelect);
                    toggleFaktorRisikoSelect(); // Call initially

                    newConditionItem.querySelector('.remove-condition').addEventListener('click', function() {
                        newConditionItem.remove();
                    });
                    conditionIndex++;
                }

                if (initialConditions.length > 0) {
                    initialConditions.forEach(cond => addCondition(cond));
                } else {
                    addCondition();
                }

                addConditionBtn.addEventListener('click', () => addCondition());
                currentGroup.querySelector('.remove-condition-group').addEventListener('click', function() {
                    currentGroup.remove();
                });
                groupIndex++;
            }

            addConditionGroupBtn.addEventListener('click', () => addConditionGroup());

            const oldConditionGroups = @json(old('condition_groups', []));
            if (oldConditionGroups.length > 0) {
                oldConditionGroups.forEach(group => {
                    addConditionGroup(group.conditions || []);
                });
            } else {
                addConditionGroup();
            }
        });
    </script>
@endpush
