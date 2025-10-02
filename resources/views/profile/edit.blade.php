@extends('layouts.app')

@section('title', 'Pengaturan Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <!-- Profile Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <div class="position-relative d-inline-block">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=667eea&color=fff&size=150"
                                alt="Profile"
                                class="rounded-circle border border-3 border-primary"
                                style="width: 120px; height: 120px; object-fit: cover;"
                                id="profileImage">
                            <button type="button" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle p-2" style="transform: translate(25%, 25%);">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h3 class="fw-bold text-dark mb-2">{{ $user->name }}</h3>
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-{{ $user->role === 'petugas' ? 'success' : 'primary' }} me-3 px-3 py-2">
                                <i class="fas fa-{{ $user->role === 'petugas' ? 'user-tie' : 'user' }} me-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                        <div class="row text-muted small">
                            <div class="col-sm-6">
                                <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                            </div>
                            <div class="col-sm-6">
                                <i class="fas fa-calendar-alt me-2"></i>Bergabung {{ $user->created_at->format('M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Profile Information -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-bold text-dark mb-1">
                            <i class="fas fa-user-edit text-primary me-2"></i>
                            Informasi Profile
                        </h5>
                        <small class="text-muted">Update informasi personal Anda</small>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3">
                                <!-- Nama Lengkap -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="name"
                                            name="name"
                                            value="{{ old('name', $user->name) }}"
                                            required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                        <input type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email', $user->email) }}"
                                            required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">No. Telepon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone text-muted"></i></span>
                                        <input type="text"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone', $user->phone) }}"
                                            required>
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- NIK untuk Masyarakat -->
                                @if($user->role === 'masyarakat')
                                <div class="col-md-6">
                                    <label for="nik" class="form-label fw-semibold">NIK</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card text-muted"></i></span>
                                        <input type="text"
                                            class="form-control @error('nik') is-invalid @enderror"
                                            id="nik"
                                            name="nik"
                                            value="{{ old('nik', $user->nik) }}"
                                            maxlength="16"
                                            placeholder="16 digit NIK">
                                        @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @endif

                                <!-- Alamat -->
                                <div class="col-12">
                                    <label for="address" class="form-label fw-semibold">Alamat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                            id="address"
                                            name="address"
                                            rows="3"
                                            placeholder="Alamat lengkap">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- System Info (Read Only) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Role</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-shield-alt text-muted"></i></span>
                                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status Account</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check-circle text-success"></i></span>
                                        <input type="text" class="form-control" value="{{ ucfirst($user->status) }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" id="updateProfileBtn">
                                        <i class="fas fa-save me-2"></i>
                                        Simpan Perubahan
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="resetForm()">
                                        <i class="fas fa-undo me-2"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="col-lg-4">
                <!-- Change Password -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title fw-bold text-dark mb-1">
                            <i class="fas fa-lock text-warning me-2"></i>
                            Keamanan Account
                        </h6>
                        <small class="text-muted">Update password untuk keamanan</small>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        id="current_password"
                                        name="current_password"
                                        required>
                                    @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-check text-muted"></i></span>
                                    <input type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning" id="changePasswordBtn">
                                    <i class="fas fa-key me-2"></i>
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Stats -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title fw-bold text-dark mb-0">
                            <i class="fas fa-chart-line text-info me-2"></i>
                            Statistik Account
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Account dibuat</span>
                            <span class="fw-semibold">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                        @if($user->role === 'masyarakat')
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Total permohonan</span>
                            <span class="fw-semibold">{{ $user->permohonans()->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Permohonan selesai</span>
                            <span class="fw-semibold text-success">{{ $user->permohonans()->where('status', 'selesai')->count() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // NIK validation
    document.getElementById('nik')?.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 16) {
            this.value = this.value.slice(0, 16);
        }
    });

    // Phone validation
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9+\-\s]/g, '');
    });

    // Form submissions
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('updateProfileBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });

    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('changePasswordBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengubah...';
    });

    // Reset form
    function resetForm() {
        document.getElementById('profileForm').reset();
        location.reload();
    }

    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let feedback = '';

        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        const colors = ['danger', 'danger', 'warning', 'info', 'success'];
        const texts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];

        // Remove existing feedback
        const existingFeedback = this.parentNode.parentNode.querySelector('.password-strength');
        if (existingFeedback) {
            existingFeedback.remove();
        }

        if (password.length > 0) {
            const feedbackDiv = document.createElement('div');
            feedbackDiv.className = `password-strength small text-${colors[strength-1]} mt-1`;
            feedbackDiv.innerHTML = `Kekuatan password: ${texts[strength-1]}`;
            this.parentNode.parentNode.appendChild(feedbackDiv);
        }
    });
</script>
@endpush