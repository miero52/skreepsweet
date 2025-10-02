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
                            <h6 class="fw-semibold text-primary">Pilih Layanan</h6>
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
            <div class="card-header">
                <h5 class="card-title fw-bold text-dark mb-1">
                    <i class="fas fa-file-plus text-primary me-2"></i>
                    Formulir Pengajuan Permohonan
                </h5>
                <small class="text-muted">Lengkapi semua informasi yang diperlukan dengan benar</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('masyarakat.store-permohonan') }}" enctype="multipart/form-data" id="permohonanForm">
                    @csrf

                    <!-- Step 1: Service Selection -->
                    <div class="form-step active" id="step1">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Jenis Layanan <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-3">Pilih jenis surat yang Anda butuhkan</p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="service-card border rounded-3 p-4 text-center position-relative" onclick="selectService('surat_keterangan')" data-service="surat_keterangan">
                                        <div class="service-icon mb-3">
                                            <i class="fas fa-certificate fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Surat Keterangan</h6>
                                        <p class="text-muted small mb-3">Surat keterangan untuk berbagai keperluan administratif seperti nikah, domisili, dll</p>
                                        <div class="badge bg-primary bg-opacity-10 text-primary">Populer</div>
                                        <input type="radio" name="jenis_layanan" value="surat_keterangan" class="d-none">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-card border rounded-3 p-4 text-center position-relative" onclick="selectService('surat_izin')" data-service="surat_izin">
                                        <div class="service-icon mb-3">
                                            <i class="fas fa-file-signature fa-3x text-success"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-2">Surat Izin</h6>
                                        <p class="text-muted small mb-3">Surat izin untuk kegiatan keagamaan, pengajian, acara religi, dll</p>
                                        <div class="badge bg-success bg-opacity-10 text-success">Tersedia</div>
                                        <input type="radio" name="jenis_layanan" value="surat_izin" class="d-none">
                                    </div>
                                </div>
                            </div>
                            @error('jenis_layanan')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="nextStep()" id="nextBtn1" disabled>
                                Lanjutkan <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Form Data -->
                    <div class="form-step" id="step2" style="display: none;">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="perihal" class="form-label fw-semibold">Perihal/Keperluan <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('perihal') is-invalid @enderror"
                                    id="perihal"
                                    name="perihal"
                                    value="{{ old('perihal') }}"
                                    placeholder="Contoh: Surat Keterangan Nikah"
                                    required>
                                @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estimasi Waktu Selesai</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    <input type="text" class="form-control" value="3-5 Hari Kerja" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="keterangan" class="form-label fw-semibold">Keterangan Tambahan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    rows="4"
                                    placeholder="Jelaskan detail permohonan Anda, tujuan penggunaan surat, atau informasi tambahan lainnya...">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">
                                Lanjutkan <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: File Upload -->
                    <div class="form-step" id="step3" style="display: none;">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Upload Dokumen Persyaratan <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-3">Upload semua dokumen yang diperlukan dalam format PDF, JPG, atau PNG</p>

                            <!-- File Drop Zone -->
                            <div class="upload-zone border border-3 border-dashed rounded-3 p-5 text-center mb-3" id="uploadZone">
                                <div class="upload-icon mb-3">
                                    <i class="fas fa-cloud-upload-alt fa-4x text-primary opacity-50"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">Drag & Drop files atau klik untuk memilih</h6>
                                <p class="text-muted small mb-3">Maksimal 2MB per file • Format: PDF, JPG, PNG</p>
                                <input type="file"
                                    class="form-control d-none @error('file_persyaratan.*') is-invalid @enderror"
                                    id="file_persyaratan"
                                    name="file_persyaratan[]"
                                    multiple
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    required>
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('file_persyaratan').click()">
                                    <i class="fas fa-plus me-2"></i>Pilih File
                                </button>
                            </div>

                            <!-- File Preview -->
                            <div id="filePreview" class="mb-3"></div>

                            @error('file_persyaratan.*')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Requirements Info -->
                        <div class="alert alert-info border-0">
                            <h6 class="alert-heading fw-semibold">
                                <i class="fas fa-info-circle me-2"></i>
                                Persyaratan Umum
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Fotocopy KTP yang masih berlaku</li>
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Fotocopy Kartu Keluarga</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Dokumen pendukung sesuai kebutuhan</li>
                                        <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Surat pengantar RT/RW (jika diperlukan)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Kirim Permohonan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentStep = 1;
    let selectedFiles = [];

    // Service Selection
    function selectService(service) {
        // Remove previous selections
        document.querySelectorAll('.service-card').forEach(card => {
            card.classList.remove('border-primary', 'border-success', 'bg-light');
        });

        // Add selection to clicked card
        const selectedCard = document.querySelector(`[data-service="${service}"]`);
        if (service === 'surat_keterangan') {
            selectedCard.classList.add('border-primary', 'bg-light');
        } else {
            selectedCard.classList.add('border-success', 'bg-light');
        }

        // Update radio button
        document.querySelector(`input[value="${service}"]`).checked = true;

        // Enable next button
        document.getElementById('nextBtn1').disabled = false;

        // Update progress
        updateProgress(1);
    }

    // Step Navigation
    function nextStep() {
        if (validateCurrentStep()) {
            document.getElementById(`step${currentStep}`).style.display = 'none';
            currentStep++;
            document.getElementById(`step${currentStep}`).style.display = 'block';
            updateProgress(currentStep);

            // Auto-focus first input in new step
            const firstInput = document.querySelector(`#step${currentStep} input, #step${currentStep} textarea`);
            if (firstInput) firstInput.focus();
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
        for (let i = 1; i <= 3; i++) {
            const circle = document.querySelector(`.row:first-child .col-md-${4*i-3} .rounded-circle`);
            const title = document.querySelector(`.row:first-child .col-md-${4*i-3} h6`);

            if (i <= step) {
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
        }
    }

    // Validation
    function validateCurrentStep() {
        switch (currentStep) {
            case 1:
                const service = document.querySelector('input[name="jenis_layanan"]:checked');
                if (!service) {
                    alert('Silakan pilih jenis layanan terlebih dahulu');
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
                if (selectedFiles.length === 0) {
                    alert('Minimal upload 1 dokumen persyaratan');
                    return false;
                }
                break;
        }
        return true;
    }

    // File Upload Handling
    document.getElementById('file_persyaratan').addEventListener('change', function(e) {
        handleFiles(this.files);
    });

    // Drag & Drop
    const uploadZone = document.getElementById('uploadZone');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        uploadZone.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
    }

    function unhighlight() {
        uploadZone.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
    }

    uploadZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        selectedFiles = Array.from(files);
        displayFiles();
    }

    function displayFiles() {
        const preview = document.getElementById('filePreview');
        preview.innerHTML = '';

        if (selectedFiles.length > 0) {
            selectedFiles.forEach((file, index) => {
                const fileDiv = document.createElement('div');
                fileDiv.className = 'alert alert-light border d-flex justify-content-between align-items-center mb-2';

                const fileInfo = document.createElement('div');
                fileInfo.innerHTML = `
                <i class="fas fa-file-${getFileIcon(file.type)} text-primary me-2"></i>
                <strong>${file.name}</strong>
                <small class="text-muted ms-2">(${formatFileSize(file.size)})</small>
            `;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-outline-danger';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.onclick = () => removeFile(index);

                fileDiv.appendChild(fileInfo);
                fileDiv.appendChild(removeBtn);
                preview.appendChild(fileDiv);
            });
        }
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        displayFiles();

        // Update file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        document.getElementById('file_persyaratan').files = dt.files;
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
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
    });
</script>

<style>
    .service-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .service-card:hover {
        border-color: #dee2e6 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .upload-zone {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-zone:hover {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }

    .form-step {
        min-height: 400px;
    }
</style>
@endpush