<div class="modal fade" id="editProfileModal">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #001B48; border: none;">
                <div class="d-flex align-items-center w-100">
                    <div class="flex-grow-1 ps-5">
                        <h4 class="modal-title fw-bold mb-0 d-flex align-items-center" style="color: #E49502;">
                            <i class="fas fa-user-edit me-3" style="font-size: 1.3rem;"></i>Edit Profil
                        </h4>
                        <small class="d-block text-white">Kelola informasi pribadi dan kesehatan Anda</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body bg-light">
                <div id="" class="row justify-content-center">
                    <div class="col-12 col-sm-10 py-5">
                        <div class="d-flex justify-content-between align-items-center pb-4">
                            <h2 class="font-semibold mb-0">
                                <i class="fas fa-user-circle me-2" style="color: #001B48;"></i>Informasi Pribadi
                            </h2>
                        </div>
                        <div class="card border-0 shadow-lg rounded-4 mb-5">
                            <div class="card-body p-5">
                                <form action="{{ route('update-profile') }}" method="POST" id="profileForm">
                                    @method('PUT')
                                    @csrf
                                    
                                    <!-- Basic Information Section -->
                                    <div class="section-divider mb-3">
                                        <h5 class="text-muted fw-bold mb-3">
                                            <i class="fas fa-id-card me-2" style="color: #001B48;"></i>Data Dasar
                                        </h5>
                                    </div>
                                    
                                    <div class="row pb-2 gy-3">
                                        <div class="col-lg-6 col-12">
                                            <label for="name" class="form-label fw-semibold mb-2">
                                                <i class="fas fa-signature me-2" style="color: #001B48;"></i>Nama Lengkap
                                            </label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-2" 
                                                name="name" id="name"
                                                placeholder="Masukkan nama Anda"
                                                value="{{ auth()->user()->name ?? '' }}"
                                                style="border-color: #001B48; transition: all 0.3s ease;">
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <label for="email" class="form-label fw-semibold mb-2">
                                                <i class="fas fa-envelope me-2" style="color: #001B48;"></i>Email
                                            </label>
                                            <input type="email" class="form-control form-control-lg rounded-3 border-2" 
                                                name="email" id="email"
                                                readonly style="border-color: #001B48; background-color: #f8f9fa;"
                                                value="{{ auth()->user()->email ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="row pb-3 gy-4">
                                        <div class="col-lg-6 col-12">
                                            <label for="gender" class="form-label fw-semibold mb-2">
                                                <i class="fas fa-venus-mars me-2" style="color: #001B48;"></i>Jenis Kelamin
                                            </label>
                                            <select class="form-select form-select-lg rounded-3 border-2" 
                                                name="gender" id="gender"
                                                style="border-color: #001B48; transition: all 0.3s ease;">
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="male" {{ auth()->user()->profile->gender === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="female" {{ auth()->user()->profile->gender === 'female' ? 'selected' : '' }}>Perempuan</option>
                                                <option value="other" {{ auth()->user()->profile->gender === 'other' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <label for="age" class="form-label fw-semibold mb-2">
                                                <i class="fas fa-birthday-cake me-2" style="color: #001B48;"></i>Usia (Tahun)
                                            </label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-2" 
                                                name="age" id="age"
                                                placeholder="Masukkan usia Anda"
                                                value="{{ auth()->user()->profile->age ?? '' }}"
                                                min="1" max="120"
                                                style="border-color: #001B48; transition: all 0.3s ease;">
                                        </div>
                                    </div>

                                    <!-- Health Information Section -->
                                    <div class="section-divider mb-3 mt-4">
                                        <h5 class="text-muted fw-bold mb-3">
                                            <i class="fas fa-heart me-2" style="color: #001B48;"></i>Data Kesehatan
                                        </h5>
                                    </div>

                                    <div class="row pb-2 gy-3">
                                        <div class="col-lg-6 col-12">
                                            <label for="height" class="form-label fw-semibold mb-2">
                                                <i class="fas fa-ruler-vertical me-2" style="color: #001B48;"></i>Tinggi Badan (cm)
                                            </label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-2 health-input" 
                                                name="height" id="height"
                                                placeholder="Contoh: 170"
                                                value="{{ auth()->user()->profile->height ?? '' }}"
                                                step="0.1" min="50" max="250"
                                                style="border-color: #001B48; transition: all 0.3s ease;">
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <label for="weight" class="form-label fw-semibold mb-2">
                                                <i class="fas fa-weight me-2" style="color: #001B48;"></i>Berat Badan (kg)
                                            </label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-2 health-input" 
                                                name="weight" id="weight"
                                                placeholder="Contoh: 70"
                                                value="{{ auth()->user()->profile->weight ?? '' }}"
                                                step="0.1" min="20" max="250"
                                                style="border-color: #001B48; transition: all 0.3s ease;">
                                        </div>
                                    </div>

                                    <!-- BMI Display Section -->
                                    <div class="row pb-2 gy-3">
                                        <div class="col-12">
                                            <div class="card border-0 rounded-4 p-3" style="background: linear-gradient(135deg, #001B4815 0%, #001B4825 100%); border: 2px solid #001B4830;">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-6">
                                                        <label for="bmi" class="form-label fw-semibold mb-2">
                                                            <i class="fas fa-chart-line me-2" style="color: #001B48;"></i>Indeks Massa Tubuh (IMT)
                                                        </label>
                                                        <div class="input-group input-group-lg">
                                                            <input type="number" class="form-control form-control-lg rounded-3 border-2" 
                                                                name="bmi" id="bmi"
                                                                placeholder="Otomatis dihitung"
                                                                readonly
                                                                value="{{ auth()->user()->profile->bmi ?? '' }}"
                                                                step="0.01"
                                                                style="border-color: #001B48; background-color: #f8f9fa;">
                                                            <span class="input-group-text rounded-3 border-2" style="border-color: #001B48; background-color: #f8f9fa;">
                                                                <small class="text-muted fw-semibold">kg/m²</small>
                                                            </span>
                                                        </div>
                                                        <small class="d-block text-muted mt-2">
                                                            <i class="fas fa-info-circle me-1" style="color: #001B48;"></i>Nilai BMI dihitung secara otomatis dari tinggi dan berat badan
                                                        </small>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <small class="text-muted mb-2">Status Kesehatan Anda:</small>
                                                            <div id="bmiStatus" class="badge rounded-pill px-4 py-2 fw-bold" style="font-size: 0.95rem;">
                                                                @if(auth()->user()->profile->bmi)
                                                                    @if(auth()->user()->profile->bmi < 18.5)
                                                                        <span class="badge bg-info">Berat Badan Kurang</span>
                                                                    @elseif(auth()->user()->profile->bmi >= 18.5 && auth()->user()->profile->bmi < 25)
                                                                        <span class="badge bg-success">Berat Badan Normal</span>
                                                                    @elseif(auth()->user()->profile->bmi >= 25 && auth()->user()->profile->bmi < 30)
                                                                        <span class="badge bg-warning text-dark">Berat Badan Berlebih</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Obesitas</span>
                                                                    @endif
                                                                @else
                                                                    <span class="badge bg-secondary">Belum Ada Data</span>
                                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4 mt-5">
                                <div class="d-grid gap-2">
                                    <button type="submit" id="btnSubmitEditProfile"
                                        class="btn btn-lg rounded-3 text-white fw-bold"
                                        style="background-color: #001B48; border: none; padding: 0.75rem 2rem; transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(0, 27, 72, 0.4)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                        <i class="fas fa-save me-2"></i>Simpan Profil
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" id="historiDiagnosis">
            <div class="col-12 col-sm-10 py-5">
                <div class="d-flex justify-content-between align-items-center pb-4">
                    <h2 class="font-semibold mb-0">
                        <i class="fas fa-history me-2" style="color: #001B48;"></i>Histori Diagnosis
                    </h2>
                </div>
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5">
                        <table class="table table-striped text-nowrap" style="width: 100%;"
                            id="historiDiagnosisTable">
                            <thead>
                                <tr class="bg-light">
                                    <th class="fw-semibold" style="color: #001B48;">No</th>
                                    <th class="fw-semibold" style="color: #001B48;">Tanggal</th>
                                    <th class="fw-semibold" style="color: #001B48;">Diagnosis Tingkat Risiko</th>
                                    <th class="fw-semibold" style="color: #001B48;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
.section-divider {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 1rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #001B48 !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 27, 72, 0.25) !important;
}

.form-control-lg,
.form-select-lg {
    height: auto;
    padding: 0.75rem 1.25rem;
    font-size: 1rem;
}

.btn-close-white:focus {
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25) !important;
}

/* Health input styling */
.health-input {
    background-color: #f8f9fa;
}

.health-input:focus {
    background-color: white;
}

/* Hover effects for form inputs */
.form-control:not(:focus),
.form-select:not(:focus) {
    transition: all 0.3s ease;
}

.form-control:hover:not(:focus),
.form-select:hover:not(:focus) {
    border-color: #667eea !important;
    background-color: #f8f9fa;
}

/* BMI Status badge animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#bmiStatus {
    animation: fadeIn 0.3s ease;
}

/* Modal styling */
.modal-content {
    border: none;
    border-radius: 0.5rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-4px);
}
</style>

<script>
// Calculate BMI automatically when weight or height changes
document.getElementById('height').addEventListener('change', calculateBMI);
document.getElementById('weight').addEventListener('change', calculateBMI);
document.getElementById('weight').addEventListener('keyup', calculateBMI);
document.getElementById('height').addEventListener('keyup', calculateBMI);

function calculateBMI() {
    const height = parseFloat(document.getElementById('height').value);
    const weight = parseFloat(document.getElementById('weight').value);
    
    if (height && weight && height > 0 && weight > 0) {
        const heightInMeters = height / 100;
        const bmi = (weight / (heightInMeters * heightInMeters)).toFixed(2);
        
        document.getElementById('bmi').value = bmi;
        updateBMIStatus(bmi);
    }
}

function updateBMIStatus(bmi) {
    const statusDiv = document.getElementById('bmiStatus');
    let statusHTML = '';
    
    if (bmi < 18.5) {
        statusHTML = '<span class="badge bg-info">Berat Badan Kurang</span>';
    } else if (bmi >= 18.5 && bmi < 25) {
        statusHTML = '<span class="badge bg-success">Berat Badan Normal</span>';
    } else if (bmi >= 25 && bmi < 30) {
        statusHTML = '<span class="badge bg-warning text-dark">Berat Badan Berlebih</span>';
    } else {
        statusHTML = '<span class="badge bg-danger">Obesitas</span>';
    }
    
    statusDiv.innerHTML = statusHTML;
}
</script>
</div>
