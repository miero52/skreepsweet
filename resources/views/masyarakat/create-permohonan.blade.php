@extends('layouts.app')

@section('title', 'Ajukan Permohonan')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <!-- Progress Steps -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                <span class="fw-bold">1</span>
                            </div>
                            <h6 class="fw-semibold text-primary">Pilih Jenis Surat</h6>
                            <small class="text-muted">Tentukan jenis surat yang dibutuhkan</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                <span class="fw-bold">2</span>
                            </div>
                            <h6 class="fw-semibold text-secondary">Isi Data</h6>
                            <small class="text-muted">Lengkapi informasi permohonan</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                <span class="fw-bold">3</span>
                            </div>
                            <h6 class="fw-semibold text-secondary">Upload & Kirim</h6>
                            <small class="text-muted">Upload dokumen dan submit</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="card">
            <div class="card-header bg-primary bg-gradient text-white">
                <h5 class="card-title fw-bold mb-1">
                    <i class="fas fa-file-plus me-2"></i>
                    Pengajuan Permohonan Surat
                </h5>
                <small>Kementerian Agama Kota Palembang</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('masyarakat.store-permohonan') }}" enctype="multipart/form-data" id="permohonanForm">
                    @csrf

                    <!-- Step 1: Jenis Surat Selection -->
                    <div class="form-step active" id="step1">
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5">Pilih Jenis Surat <span class="text-danger">*</span></label>
                            <p class="text-muted mb-3">Pilih jenis surat yang sesuai dengan kebutuhan Anda</p>

                            <select class="form-select form-select-lg" id="jenis_surat" name="jenis_surat" required onchange="showRequirements()">
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="izin_penelitian" data-requirements='["Surat Dari Instansi","Proposal Penelitian"]'>
                                    Surat Izin Penelitian
                                </option>
                                <option value="rohaniwan_islam" data-requirements='["Surat Permohonan"]'>
                                    Surat Permohonan Rohaniwan Islam
                                </option>
                                <option value="rohaniwan_buddha" data-requirements='["Surat Permohonan"]'>
                                    Surat Permohonan Rohaniwan Buddha
                                </option>
                                <option value="rohaniwan_kristen" data-requirements='["Surat Permohonan"]'>
                                    Surat Permohonan Rohaniwan Kristen
                                </option>
                                <option value="pengukuran_kiblat" data-requirements='["Surat Permohonan","SK Pengurus Masjid","Fotocopy Sertifikat Tanah Masjid","KTP Yang Mengurus"]'>
                                    Surat Pengukuran Arah Kiblat
                                </option>
                                <option value="izin_majelis" data-requirements='["Surat Permohonan","SK Pengurus Yang Diketahui Ketua RT"]'>
                                    Surat Izin Majelis
                                </option>
                                <option value="izin_tpq" data-requirements='["Surat Permohonan","SK Pengurus","KTP Pengurus","Foto Kegiatan"]'>
                                    Permohonan Izin Pendirian Taman Pendidikan Quran (TPQ)
                                </option>
                            </select>

                            @error('jenis_surat')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror

                            <!-- Requirements Display -->
                            <div id="requirements" class="mt-4" style="display: none;">
                                <div class="alert alert-info border-0 shadow-sm">
                                    <h6 class="alert-heading fw-semibold">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        Persyaratan yang Diperlukan
                                    </h6>
                                    <div id="requirementsList"></div>
                                    <hr>
                                    <p class="mb-0 small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Pastikan semua dokumen sudah disiapkan sebelum melanjutkan
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-lg" onclick="nextStep()" id="nextBtn1" disabled>
                                Lanjutkan <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Form Data -->
                    <div class="form-step" id="step2" style="display: none;">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="perihal" class="form-label fw-semibold">
                                    Perihal/Keperluan <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('perihal') is-invalid @enderror"
                                    id="perihal"
                                    name="perihal"
                                    value="{{ old('perihal') }}"
                                    placeholder="Contoh: Untuk keperluan skripsi"
                                    required>
                                <small class="text-muted">Jelaskan secara singkat tujuan permohonan</small>
                                @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estimasi Waktu Selesai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-clock text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light" value="3-5 Hari Kerja" readonly>
                                </div>
                                <small class="text-muted">Setelah semua persyaratan lengkap</small>
                            </div>

                            <div class="col-12">
                                <label for="keterangan" class="form-label fw-semibold">Keterangan Tambahan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    rows="4"
                                    placeholder="Jelaskan detail permohonan Anda, informasi tambahan, atau catatan khusus...">{{ old('keterangan') }}</textarea>
                                <small class="text-muted">Opsional - Tambahkan informasi yang menurut Anda penting</small>
                                @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevStep()">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-primary btn-lg" onclick="nextStep()">
                                Lanjutkan <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: File Upload -->
                    <div class="form-step" id="step3" style="display: none;">
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5">Upload Dokumen Persyaratan <span class="text-danger">*</span></label>
                            <p class="text-muted mb-3">Upload dokumen sesuai dengan persyaratan yang telah ditentukan</p>

                            <!-- Dynamic File Upload Fields -->
                            <div id="dynamicFileUploads"></div>

                            @error('file_persyaratan.*')
                            <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Requirements Reminder -->
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <h6 class="alert-heading fw-semibold">
                                <i class="fas fa-info-circle me-2"></i>
                                Persyaratan yang Harus Dilengkapi:
                            </h6>
                            <div id="requirementsSummary"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevStep()">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Kirim Permohonan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-3 border-0 bg-light">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">
                    <i class="fas fa-lightbulb text-warning me-2"></i>
                    Tips Pengajuan Permohonan
                </h6>
                <ul class="mb-0 small text-muted">
                    <li>Pastikan semua dokumen sudah lengkap dan jelas (tidak blur)</li>
                    <li>Gunakan format file yang diizinkan (PDF, JPG, PNG)</li>
                    <li>Permohonan akan diproses maksimal 3-5 hari kerja</li>
                    <li>Anda akan mendapat notifikasi melalui email saat status berubah</li>
                </ul>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    let currentStep = 1;
    let currentRequirements = [];
    let uploadedFiles = {};

    // Show Requirements
    function showRequirements() {
        const dropdown = document.getElementById('jenis_surat');
        const selectedOption = dropdown.options[dropdown.selectedIndex];

        if (selectedOption.value) {
            currentRequirements = JSON.parse(selectedOption.dataset.requirements);

            let html = '<ul class="list-unstyled mb-0">';
            currentRequirements.forEach((req, index) => {
                html += `
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>${index + 1}.</strong> ${req}
                    </li>
                `;
            });
            html += '</ul>';

            document.getElementById('requirementsList').innerHTML = html;
            document.getElementById('requirements').style.display = 'block';

            // Enable next button
            document.getElementById('nextBtn1').disabled = false;
        } else {
            document.getElementById('requirements').style.display = 'none';
            document.getElementById('nextBtn1').disabled = true;
        }
    }

    // Generate Dynamic Upload Fields
    function generateUploadFields() {
        const container = document.getElementById('dynamicFileUploads');
        container.innerHTML = '';
        uploadedFiles = {};

        currentRequirements.forEach((requirement, index) => {
            const fieldHtml = `
                <div class="card mb-3 border shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-0">
                                    <span class="badge bg-primary me-2">${index + 1}</span>
                                    ${requirement} <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-5">
                                <input type="file" 
                                       class="form-control file-input" 
                                       id="file_${index}"
                                       name="file_persyaratan[]"
                                       data-requirement="${requirement}"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       required
                                       onchange="handleFileSelect(${index}, this)">
                                <small class="text-muted">Format: PDF, JPG, PNG (Max 2MB)</small>
                            </div>
                            <div class="col-md-3" id="preview_${index}">
                                <span class="text-muted small">
                                    <i class="fas fa-cloud-upload-alt me-1"></i>Belum upload
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += fieldHtml;
        });

        // Update summary
        updateRequirementsSummary();
    }

    // Handle File Select
    function handleFileSelect(index, input) {
        const file = input.files[0];
        const previewDiv = document.getElementById(`preview_${index}`);

        if (file) {
            // Validate file size
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                input.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Format file harus PDF, JPG, atau PNG!');
                input.value = '';
                return;
            }

            uploadedFiles[index] = file;

            // Show preview
            previewDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-${getFileIcon(file.type)} text-success me-2 fs-5"></i>
                    <div class="flex-grow-1">
                        <small class="d-block fw-semibold text-success">
                            <i class="fas fa-check-circle me-1"></i>Terupload
                        </small>
                        <small class="text-muted">${formatFileSize(file.size)}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeFile(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            updateRequirementsSummary();
        }
    }

    // Remove File
    function removeFile(index) {
        const input = document.getElementById(`file_${index}`);
        input.value = '';
        delete uploadedFiles[index];

        const previewDiv = document.getElementById(`preview_${index}`);
        previewDiv.innerHTML = `
            <span class="text-muted small">
                <i class="fas fa-cloud-upload-alt me-1"></i>Belum upload
            </span>
        `;

        updateRequirementsSummary();
    }

    // Update Requirements Summary
    function updateRequirementsSummary() {
        const summary = document.getElementById('requirementsSummary');
        const uploadedCount = Object.keys(uploadedFiles).length;
        const totalRequired = currentRequirements.length;

        let html = `
            <div class="d-flex align-items-center mb-3">
                <div class="progress flex-grow-1 me-3" style="height: 25px;">
                    <div class="progress-bar ${uploadedCount === totalRequired ? 'bg-success' : 'bg-primary'}" 
                         style="width: ${(uploadedCount / totalRequired) * 100}%">
                        ${uploadedCount} / ${totalRequired}
                    </div>
                </div>
                <span class="fw-semibold ${uploadedCount === totalRequired ? 'text-success' : 'text-primary'}">
                    ${uploadedCount === totalRequired ? 'Lengkap!' : 'Belum Lengkap'}
                </span>
            </div>
            <ul class="list-unstyled mb-0">
        `;

        currentRequirements.forEach((req, index) => {
            const isUploaded = uploadedFiles.hasOwnProperty(index);
            html += `
                <li class="mb-1">
                    <i class="fas fa-${isUploaded ? 'check-circle text-success' : 'circle text-muted'} me-2"></i>
                    ${req}
                    ${isUploaded ? '<span class="badge bg-success ms-2">✓</span>' : ''}
                </li>
            `;
        });

        html += '</ul>';
        summary.innerHTML = html;
    }

    // Step Navigation
    function nextStep() {
        if (validateCurrentStep()) {
            // Generate upload fields when entering step 3
            if (currentStep === 2) {
                // Tunggu sedikit agar DOM Step 3 muncul dulu
                setTimeout(() => {
                    generateUploadFields();
                }, 100);
            }


            document.getElementById(`step${currentStep}`).style.display = 'none';
            currentStep++;
            document.getElementById(`step${currentStep}`).style.display = 'block';
            updateProgress(currentStep);

            // Auto-focus first input
            setTimeout(() => {
                const firstInput = document.querySelector(`#step${currentStep} input:not([type="file"]):not([type="checkbox"]), #step${currentStep} textarea`);
                if (firstInput && firstInput.style.display !== 'none') firstInput.focus();
            }, 100);
        }
    }

    function prevStep() {
        document.getElementById(`step${currentStep}`).style.display = 'none';
        currentStep--;
        document.getElementById(`step${currentStep}`).style.display = 'block';
        updateProgress(currentStep);
    }

    // Progress Update
    function updateProgress(step) {
        const cols = document.querySelectorAll('.card-body > .row:first-child .col-md-4');
        cols.forEach((col, index) => {
            const circle = col.querySelector('.rounded-circle');
            const title = col.querySelector('h6');

            if (index < step) {
                circle.classList.remove('bg-secondary');
                circle.classList.add('bg-primary');
                title.classList.remove('text-secondary');
                title.classList.add('text-primary');
            } else {
                circle.classList.remove('bg-primary');
                circle.classList.add('bg-secondary');
                title.classList.remove('text-primary');
                title.classList.add('text-secondary');
            }
        });
    }

    // Validation
    function validateCurrentStep() {
        switch (currentStep) {
            case 1:
                const jenisSurat = document.getElementById('jenis_surat').value;
                if (!jenisSurat) {
                    alert('Silakan pilih jenis surat terlebih dahulu');
                    document.getElementById('jenis_surat').focus();
                    return false;
                }
                break;
            case 2:
                const perihal = document.getElementById('perihal').value;
                if (!perihal.trim()) {
                    alert('Perihal harus diisi');
                    document.getElementById('perihal').focus();
                    return false;
                }
                break;
            case 3:
                const uploadedCount = Object.keys(uploadedFiles).length;
                const totalRequired = currentRequirements.length;

                if (uploadedCount < totalRequired) {
                    alert(`Harap upload semua dokumen persyaratan!\nTelah upload: ${uploadedCount}/${totalRequired}`);
                    return false;
                }
                break;
        }
        return true;
    }

    function getFileIcon(mimeType) {
        if (mimeType.includes('pdf')) return 'pdf';
        if (mimeType.includes('image')) return 'image';
        return 'alt';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form Submission
    document.getElementById('permohonanForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim Permohonan...';
    });
</script>

<style>
    .form-step {
        min-height: 400px;
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #jenis_surat {
        border: 2px solid #dee2e6;
        padding: 12px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    #jenis_surat:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    #requirements {
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }

        to {
            opacity: 1;
            max-height: 500px;
        }
    }

    .btn-lg {
        padding: 12px 30px;
        font-size: 1.1rem;
    }

    .file-input {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-input:hover {
        border-color: #0d6efd;
    }

    #dynamicFileUploads .card {
        transition: all 0.3s ease;
    }

    #dynamicFileUploads .card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .progress {
        border-radius: 10px;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endpush