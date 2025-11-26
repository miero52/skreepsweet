@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<!-- Statistics Overview -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">{{ $stats['menunggu'] }}</h3>
                    <p class="mb-0 opacity-75">Menunggu Review</p>
                </div>
                <div class="position-relative">
                    <i class="fas fa-clock fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">{{ $stats['diproses'] }}</h3>
                    <p class="mb-0 opacity-75">Sedang Diproses</p>
                </div>
                <div class="position-relative">
                    <i class="fas fa-sync-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">{{ $stats['selesai'] }}</h3>
                    <p class="mb-0 opacity-75">Selesai</p>
                </div>
                <div class="position-relative">
                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card stat-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">{{ $stats['ditolak'] }}</h3>
                    <p class="mb-0 opacity-75">Ditolak</p>
                </div>
                <div class="position-relative">
                    <i class="fas fa-times-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold text-dark mb-1">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Quick Actions
                        </h5>

                    </div>
                    <div class="col-md-12">
                        <div class="d-flex justify-content-start align-items-center flex-wrap gap-2 mt-2">
                            <a href="{{ route('petugas.users.index') }}" class="btn btn-outline-dark d-flex align-items-center gap-2 px-3">
                                <i class="fas fa-users"></i>
                                <span>Kelola Pengguna</span>
                            </a>

                            <button class="btn btn-outline-primary d-flex align-items-center gap-2 px-3" onclick="exportData()">
                                <i class="fas fa-download"></i>
                                <span>Export Excel</span>
                            </button>

                            <a href="{{ route('petugas.laporan') }}" class="btn btn-outline-danger d-flex align-items-center gap-2 px-3">
                                <i class="fas fa-file-pdf"></i>
                                <span>Laporan PDF</span>
                            </a>

                            <button class="btn btn-outline-success d-flex align-items-center gap-2 px-3" onclick="refreshData()">
                                <i class="fas fa-sync"></i>
                                <span>Refresh</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="fas fa-filter text-primary me-2"></i>
                    Filter & Pencarian
                </h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Status</label>
                        <select class="form-select" id="filterStatus" onchange="filterData()">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Jenis Layanan</label>
                        <select class="form-select" id="filterJenis" onchange="filterData()">
                            <option value="">Semua Jenis</option>
                            <option value="surat_keterangan">Surat Keterangan</option>
                            <option value="surat_izin">Surat Izin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Pencarian</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari nomor permohonan, nama pemohon..." onkeyup="filterData()">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            <i class="fas fa-list text-primary me-2"></i>
                            Daftar Permohonan
                        </h5>
                        <small class="text-muted">Management dan review permohonan dari masyarakat</small>
                    </div>
                    <div class="badge bg-primary">
                        <span id="totalCount">{{ $permohonans->count() }}</span> Permohonan
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($permohonans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="permohonansTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">Permohonan</th>
                                <th class="border-0 fw-semibold">Pemohon</th>
                                <th class="border-0 fw-semibold">Jenis & Perihal</th>
                                <th class="border-0 fw-semibold">Tanggal</th>
                                <th class="border-0 fw-semibold">Status</th>
                                <th class="border-0 fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permohonans as $permohonan)
                            <tr data-status="{{ $permohonan->status }}" data-jenis="{{ $permohonan->jenis_layanan }}" class="table-row">
                                <td>
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-primary">{{ $permohonan->nomor_permohonan }}</h6>
                                        <div class="d-flex align-items-center">
                                            @if($permohonan->file_persyaratan && count($permohonan->file_persyaratan) > 0)
                                            <span class="badge bg-success bg-opacity-10 text-success me-2">
                                                <i class="fas fa-paperclip me-1"></i>{{ count($permohonan->file_persyaratan) }} file
                                            </span>
                                            @endif
                                            @if($permohonan->status === 'selesai' && $permohonan->file_hasil)
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-check me-1"></i>Hasil tersedia
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($permohonan->user->name) }}&background=667eea&color=fff&size=40"
                                            class="rounded-circle me-3"
                                            width="40" height="40">
                                        <div>
                                            <h6 class="mb-0 fw-medium">{{ $permohonan->user->name }}</h6>
                                            <small class="text-muted">{{ $permohonan->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge bg-light text-dark border mb-2">
                                            <i class="fas fa-file-alt me-1"></i>
                                            {{ str_replace('_', ' ', ucwords($permohonan->jenis_layanan)) }}
                                        </span>
                                        <div class="fw-medium text-dark">{{ Str::limit($permohonan->perihal, 40) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="fw-medium">{{ $permohonan->tanggal_pengajuan->format('d M Y') }}</div>
                                        <div class="text-muted">{{ $permohonan->tanggal_pengajuan->format('H:i') }}</div>
                                        <div class="text-muted">{{ $permohonan->tanggal_pengajuan->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td>
                                    @switch($permohonan->status)
                                    @case('menunggu')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i> Menunggu
                                    </span>
                                    @break
                                    @case('diproses')
                                    <span class="badge bg-info">
                                        <i class="fas fa-sync-alt me-1"></i> Diproses
                                    </span>
                                    @break
                                    @case('selesai')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Selesai
                                    </span>
                                    @break
                                    @case('ditolak')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Ditolak
                                    </span>
                                    @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $permohonan->id }}"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if($permohonan->status !== 'selesai' && $permohonan->status !== 'ditolak')
                                        <button class="btn btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal{{ $permohonan->id }}"
                                            title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-outline-secondary" disabled title="Sudah Diproses">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-inbox fa-4x text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted fw-semibold">Belum Ada Permohonan</h5>
                    <p class="text-muted mb-0">Permohonan dari masyarakat akan muncul di sini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detail & Status Update Modals -->
@foreach($permohonans as $permohonan)

<div class="modal fade" id="detailModal{{ $permohonan->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Detail Permohonan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="border rounded-3 p-4">
                            <h6 class="fw-semibold text-primary mb-3">
                                <i class="fas fa-file-text me-2"></i>Informasi Permohonan
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small text-muted">Nomor Permohonan</label>
                                    <div class="fw-semibold">{{ $permohonan->nomor_permohonan }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted">Jenis Layanan</label>
                                    <div class="fw-semibold">{{ str_replace('_', ' ', ucwords($permohonan->jenis_layanan)) }}</div>
                                </div>

                                <div class="col-12">
                                    <label class="small text-muted">Perihal</label>
                                    <div class="fw-semibold">{{ $permohonan->perihal }}</div>
                                </div>

                                @if($permohonan->keterangan)
                                <div class="col-12">
                                    <label class="small text-muted">Keterangan</label>
                                    <div class="fw-semibold">{{ $permohonan->keterangan }}</div>
                                </div>
                                @endif

                                <!-- Pemohon Info -->
                                <div class="col-md-6">
                                    <label class="small text-muted">Nama Pemohon</label>
                                    <div class="fw-semibold">{{ $permohonan->user->name }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted">Email</label>
                                    <div class="fw-semibold">{{ $permohonan->user->email }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted">No. Telepon</label>
                                    <div class="fw-semibold">{{ $permohonan->user->phone ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted">NIK</label>
                                    <div class="fw-semibold">{{ $permohonan->user->nik ?? '-' }}</div>
                                </div>

                                @if($permohonan->user->address)
                                <div class="col-12">
                                    <label class="small text-muted">Alamat</label>
                                    <div class="fw-semibold">{{ $permohonan->user->address }}</div>
                                </div>
                                @endif
                            </div>

                            <!-- File Persyaratan yang bisa dilihat/download petugas -->
                            @if($permohonan->file_persyaratan && count($permohonan->file_persyaratan) > 0)
                            <div class="mt-4">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="fas fa-paperclip me-2"></i>Dokumen Persyaratan
                                </h6>
                                <div class="row g-2">
                                    @foreach($permohonan->file_persyaratan as $index => $file)
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <span class="fw-medium">{{ $file }}</span>
                                            </div>
                                            <a href="{{ asset('uploads/persyaratan/' . $file) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Lihat
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded-3 p-4 h-100">
                            <h6 class="fw-semibold text-success mb-3">
                                <i class="fas fa-tasks me-2"></i>Status & Timeline
                            </h6>

                            <div class="mb-3">
                                <label class="small text-muted">Status Saat Ini</label>
                                <div>
                                    @switch($permohonan->status)
                                    @case('menunggu')
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i> Menunggu Verifikasi
                                    </span>
                                    @break
                                    @case('diproses')
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-sync-alt me-1"></i> Sedang Diproses
                                    </span>
                                    @break
                                    @case('selesai')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i> Selesai
                                    </span>
                                    @break
                                    @case('ditolak')
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times-circle me-1"></i> Ditolak
                                    </span>
                                    @break
                                    @endswitch
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted">Tanggal Pengajuan</label>
                                <div class="fw-semibold">{{ $permohonan->tanggal_pengajuan->format('d M Y, H:i') }}</div>
                            </div>

                            @if($permohonan->tanggal_diproses)
                            <div class="mb-3">
                                <label class="small text-muted">Tanggal Diproses</label>
                                <div class="fw-semibold">{{ $permohonan->tanggal_diproses->format('d M Y, H:i') }}</div>
                            </div>
                            @endif

                            @if($permohonan->tanggal_selesai)
                            <div class="mb-3">
                                <label class="small text-muted">Tanggal Selesai</label>
                                <div class="fw-semibold">{{ $permohonan->tanggal_selesai->format('d M Y, H:i') }}</div>
                            </div>
                            @endif

                            @if($permohonan->catatan_petugas)
                            <div class="mt-4">
                                <label class="small text-muted">Catatan Petugas</label>
                                <div class="alert alert-info border-0 mt-2">
                                    {{ $permohonan->catatan_petugas }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal - TAMBAHKAN YANG INI -->
@if($permohonan->status !== 'selesai' && $permohonan->status !== 'ditolak')
<div class="modal fade" id="statusModal{{ $permohonan->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('petugas.update-status', $permohonan->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit text-success me-2"></i>
                        Update Status Permohonan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Info Permohonan -->
                    <div class="alert alert-light border">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>No. Permohonan:</strong><br>
                                {{ $permohonan->nomor_permohonan }}
                            </div>
                            <div class="col-md-6">
                                <strong>Pemohon:</strong><br>
                                {{ $permohonan->user->name }}
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Perihal:</strong> {{ $permohonan->perihal }}
                        </div>
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Update Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required onchange="toggleFileUpload(this, '{{ $permohonan->id }}')">
                            <option value="">Pilih Status</option>
                            <option value="diproses" {{ $permohonan->status == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <!-- File Upload (conditional) -->
                    <div id="fileUploadSection{{ $permohonan->id }}" class="mb-3" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Upload File Hasil Surat (PDF)</strong> - Wajib untuk status "Selesai"
                        </div>

                        @if($permohonan->file_hasil)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            File hasil sudah ada:
                            <a href="{{ asset('uploads/hasil/' . $permohonan->file_hasil) }}" target="_blank" class="alert-link">
                                {{ $permohonan->file_hasil }}
                            </a>
                        </div>
                        @endif

                        <input type="file"
                            name="file_hasil"
                            class="form-control"
                            accept=".pdf">
                        <small class="text-muted">Format: PDF | Max: 5MB</small>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan untuk Pemohon</label>
                        <textarea name="catatan_petugas"
                            class="form-control"
                            rows="3"
                            placeholder="Tambahkan catatan atau keterangan...">{{ $permohonan->catatan_petugas }}</textarea>
                        <small class="text-muted">Catatan ini akan dikirim via email kepada pemohon</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn{{ $permohonan->id }}">
                        <i class="fas fa-save me-1"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endforeach
@endsection


@push('scripts')
<script>
    function filterData() {
        const statusFilter = document.getElementById('filterStatus').value;
        const jenisFilter = document.getElementById('filterJenis').value;
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#permohonansTable tbody tr');

        let visibleCount = 0;

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const jenis = row.getAttribute('data-jenis');
            const text = row.textContent.toLowerCase();

            let show = true;

            if (statusFilter && status !== statusFilter) show = false;
            if (jenisFilter && jenis !== jenisFilter) show = false;
            if (searchInput && !text.includes(searchInput)) show = false;

            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        document.getElementById('totalCount').textContent = visibleCount;
    }

    function resetFilter() {
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterJenis').value = '';
        document.getElementById('searchInput').value = '';
        filterData();
    }

    function exportData() {
        const table = document.getElementById('permohonansTable');
        const visibleRows = Array.from(table.querySelectorAll('tbody tr')).filter(row =>
            row.style.display !== 'none'
        );

        if (visibleRows.length === 0) {
            alert('Tidak ada data untuk di-export');
            return;
        }

        // Enhanced CSV dengan kolom terpisah
        let csvContent = "data:text/csv;charset=utf-8,\uFEFF"; // BOM for UTF-8

        // Header laporan (merged cells - akan di-handle manual di Excel)
        csvContent += "LAPORAN DATA PERMOHONAN SILAP,,,,,,,,\n";
        csvContent += "Kementerian Agama Palembang,,,,,,,,\n";
        csvContent += `Tanggal Export: ${new Date().toLocaleDateString('id-ID', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    })},,,,,,,,\n`;
        csvContent += `Total Data: ${visibleRows.length} permohonan,,,,,,,,\n`;
        csvContent += ",,,,,,,,\n"; // Empty line

        // Column headers - setiap data di kolom terpisah
        csvContent += "No,Nomor Permohonan,Nama Pemohon,Email,Jenis Layanan,Perihal,Tanggal Pengajuan,Jam,Status\n";

        // Data rows dengan kolom terpisah
        visibleRows.forEach((row, index) => {
            const cols = row.querySelectorAll('td');

            // Extract data dengan error handling
            const nomor = cols[0]?.querySelector('h6')?.textContent?.trim() || '-';
            const nama = cols[1]?.querySelector('h6')?.textContent?.trim() || '-';
            const email = cols[1]?.querySelector('small')?.textContent?.trim() || '-';
            const jenis = cols[2]?.querySelector('.badge')?.textContent?.trim()?.replace(/\s+/g, ' ') || '-';
            const perihal = cols[2]?.querySelector('.fw-medium')?.textContent?.trim() || '-';
            const tanggal = cols[3]?.querySelector('.fw-medium')?.textContent?.trim() || '-';
            const waktu = cols[3]?.querySelector('.text-muted')?.textContent?.trim() || '-';
            const status = cols[4]?.querySelector('.badge')?.textContent?.trim()?.replace(/\s+/g, ' ') || '-';

            // Clean data - hapus koma dan karakter bermasalah
            const cleanNomor = nomor.replace(/[",]/g, '');
            const cleanNama = nama.replace(/[",]/g, '');
            const cleanEmail = email.replace(/[",]/g, '');
            const cleanJenis = jenis.replace(/[",]/g, '').replace(/^\s*[^\w\s]\s*/, ''); // hapus icon
            const cleanPerihal = perihal.replace(/[",]/g, '');
            const cleanTanggal = tanggal.replace(/[",]/g, '');
            const cleanWaktu = waktu.replace(/[",]/g, '');
            const cleanStatus = status.replace(/[",]/g, '').replace(/^\s*[^\w\s]\s*/, ''); // hapus icon

            // Buat array data untuk row ini
            const rowData = [
                index + 1, // No
                cleanNomor, // Nomor Permohonan
                cleanNama, // Nama Pemohon
                cleanEmail, // Email
                cleanJenis, // Jenis Layanan
                cleanPerihal, // Perihal
                cleanTanggal, // Tanggal
                cleanWaktu, // Jam
                cleanStatus // Status
            ];

            // Tambahkan ke CSV dengan quotes untuk safety
            csvContent += `"${rowData.join('","')}"\n`;
        });

        // Footer dengan kolom kosong untuk alignment
        csvContent += ",,,,,,,,\n";
        csvContent += "Keterangan Status:,,,,,,,,\n";
        csvContent += "Menunggu,Permohonan baru masuk menunggu review petugas,,,,,,,\n";
        csvContent += "Diproses,Permohonan sedang diproses oleh petugas,,,,,,,\n";
        csvContent += "Selesai,Permohonan selesai hasil dapat diunduh,,,,,,,\n";
        csvContent += "Ditolak,Permohonan ditolak dengan alasan tertentu,,,,,,,\n";
        csvContent += ",,,,,,,,\n";
        csvContent += "--- End of Report ---,,,,,,,,\n";

        // Generate filename dengan timestamp
        const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, -5);
        const filename = `Laporan_Permohonan_${timestamp}.csv`;

        // Download file
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Show success message
        alert(`Data berhasil di-export ke format Excel!\nFile: ${filename}\n\nTips: Buka file di Excel untuk hasil terbaik`);
    }

    function refreshData() {
        // Show loading state
        const refreshBtn = document.querySelector('button[onclick="refreshData()"]');
        const originalText = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Refreshing...';
        refreshBtn.disabled = true;

        // Reload page after short delay
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }

    function toggleFileUpload(select, id) {
        const fileSection = document.getElementById('fileUploadSection' + id);
        const submitBtn = document.getElementById('submitBtn' + id);

        if (select.value === 'selesai') {
            fileSection.style.display = 'block';
            submitBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Selesaikan Permohonan';
            submitBtn.className = 'btn btn-success';
        } else if (select.value === 'ditolak') {
            fileSection.style.display = 'none';
            submitBtn.innerHTML = '<i class="fas fa-times-circle me-1"></i> Tolak Permohonan';
            submitBtn.className = 'btn btn-danger';
        } else {
            fileSection.style.display = 'none';
            submitBtn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Proses Permohonan';
            submitBtn.className = 'btn btn-primary';
        }
    }
</script>
@endpush