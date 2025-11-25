@extends('layouts.admin.app')

@section('title', 'Edit Aturan')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>Edit Aturan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.beranda') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.rules.index') }}">Manajemen Aturan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Aturan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card p-3 shadow-sm mb-4">
        <form action="{{ route('admin.rules.update', $rule->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Aturan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $rule->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $rule->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="tingkat_risiko_id" class="form-label">Tingkat Risiko Hasil <span class="text-danger">*</span></label>
                <select class="form-select @error('tingkat_risiko_id') is-invalid @enderror" id="tingkat_risiko_id" name="tingkat_risiko_id" required>
                    <option value="">Pilih Tingkat Risiko</option>
                    @foreach($tingkatRisikos as $tr)
                        <option value="{{ $tr->id }}" {{ old('tingkat_risiko_id', $rule->tingkat_risiko_id) == $tr->id ? 'selected' : '' }}>{{ $tr->kode }} - {{ $tr->tingkat_risiko }}</option>
                    @endforeach
                </select>
                @error('tingkat_risiko_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" value="{{ old('priority', $rule->priority) }}" min="0" required>
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

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.rules.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let groupIndex = 0;
            const conditionGroupsContainer = document.getElementById('conditionGroupsContainer');
            const addConditionGroupBtn = document.getElementById('addConditionGroup');
            const faktorRisikos = @json($faktorRisikos); // Pass FaktorRisikos to JS
            const existingRuleData = @json($rule->load('conditionGroups.conditions'));

            function addConditionGroup(initialConditions = []) {
                const currentGroupIndex = groupIndex; // Capture current groupIndex for this specific group
                const newGroupHtml = `
                    <div class="card border-primary p-3 mb-3 condition-group" data-group-index="${currentGroupIndex}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-primary">Grup Kondisi #${currentGroupIndex + 1} (AND)</h5>
                            <button type="button" class="btn btn-danger btn-sm remove-condition-group"><i class="fas fa-trash"></i> Hapus Grup</button>
                        </div>
                        <div class="conditions-container">
                            <!-- Conditions will be added here -->
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-3 add-condition"><i class="fas fa-plus"></i> Tambah Kondisi (AND)</button>
                    </div>
                `;
                conditionGroupsContainer.insertAdjacentHTML('beforeend', newGroupHtml);

                const currentGroupElement = conditionGroupsContainer.lastElementChild;
                const conditionsContainer = currentGroupElement.querySelector('.conditions-container');
                const addConditionBtn = currentGroupElement.querySelector('.add-condition');
                
                let conditionItemIndex = 0; // Use a new variable for condition index within each group
                
                function addCondition(initialData = {}) {
                    const newConditionHtml = `
                        <div class="row mb-3 pb-3 border-bottom condition-item" data-condition-index="${conditionItemIndex}">
                            <div class="col-md-3">
                                <label class="form-label">Tipe Kondisi</label>
                                <select name="condition_groups[${currentGroupIndex}][conditions][${conditionItemIndex}][type]" class="form-select condition-type" required>
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
                                <select name="condition_groups[${currentGroupIndex}][conditions][${conditionItemIndex}][faktor_risiko_id]" class="form-select faktor-risiko-select" style="display: none;">
                                    <option value="">Pilih Faktor</option>
                                    ${faktorRisikos.map(fr => `<option value="${fr.id}">${fr.kode} - ${fr.name}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Operator</label>
                                <select name="condition_groups[${currentGroupIndex}][conditions][${conditionItemIndex}][operator]" class="form-select operator-select" required>
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
                                <input type="number" name="condition_groups[${currentGroupIndex}][conditions][${conditionItemIndex}][value]" class="form-control condition-value" value="0" required>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="button" class="btn btn-danger btn-sm remove-condition"><i class="fas fa-minus"></i> Hapus</button>
                            </div>
                        </div>
                    `;
                    conditionsContainer.insertAdjacentHTML('beforeend', newConditionHtml);
                    const newConditionItem = conditionsContainer.lastElementChild;

                    newConditionItem.querySelector('.condition-type').value = initialData.type || '';
                    newConditionItem.querySelector('.faktor-risiko-select').value = initialData.faktor_risiko_id || '';
                    newConditionItem.querySelector('.operator-select').value = initialData.operator || '==';
                    newConditionItem.querySelector('.condition-value').value = initialData.value !== undefined ? initialData.value : 0;

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
                    toggleFaktorRisikoSelect();

                    newConditionItem.querySelector('.remove-condition').addEventListener('click', function() {
                        newConditionItem.remove();
                    });
                    conditionItemIndex++;
                }

                if (initialConditions.length > 0) {
                    initialConditions.forEach(cond => addCondition(cond));
                } else {
                    addCondition();
                }

                addConditionBtn.addEventListener('click', () => addCondition());
                currentGroupElement.querySelector('.remove-condition-group').addEventListener('click', function() {
                    currentGroupElement.remove();
                });
                groupIndex++;
            }

            addConditionGroupBtn.addEventListener('click', () => addConditionGroup());

            const oldConditionGroups = @json(old('condition_groups', []));
            const initialGroups = oldConditionGroups.length > 0 ? oldConditionGroups : existingRuleData.condition_groups;

            if (initialGroups.length > 0) {
                initialGroups.forEach(group => {
                    addConditionGroup(group.conditions || []);
                });
            } else {
                addConditionGroup();
            }
        });
    </script>
@endpush
