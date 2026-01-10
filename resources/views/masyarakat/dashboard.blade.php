@extends('layouts.app')

@section('title', 'Dashboard Masyarakat')

@section('content')
<!-- Quick Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">{{ $permohonans->count() }}</h3>
                    <p class="mb-0 opacity-75">Total Permohonan</p>
                </div>
                <i class="fas fa-file-alt fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">{{ $permohonans->where('status', 'menunggu')->count() }}</h3>
                    <p class="mb-0 opacity-75">Menunggu</p>
                </div>
                <i class="fas fa-clock fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">{{ $permohonans->whereIn('status', ['diproses', 'disetujui_pimpinan'])->count() }}
                    </h3>
                    <p class="mb-0 opacity-75">Diproses</p>
                </div>
                <i class="fas fa-sync-alt fa-2x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">{{ $permohonans->where('status', 'selesai')->count() }}</h3>
                    <p class="mb-0 opacity-75">Selesai</p>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body text-center py-5">
                <i class="fas fa-plus-circle fa-4x mb-4 opacity-75"></i>
                <h3 class="fw-bold mb-3">Ajukan Permohonan Baru</h3>
                <p class="lead mb-4 opacity-75">Proses pengajuan surat keterangan atau surat izin dengan mudah dan cepat</p>
                <a href="{{ route('masyarakat.create-permohonan') }}" class="btn btn-light btn-lg px-5">
                    <i class="fas fa-paper-plane me-2"></i>
                    Mulai Pengajuan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            <i class="fas fa-history text-primary me-2"></i>
                            Riwayat Permohonan
                        </h5>
                        <small class="text-muted">Daftar permohonan yang telah Anda ajukan</small>
                    </div>
                    @if($permohonans->count() > 0)
                    <div class="badge bg-primary">{{ $permohonans->count() }} Permohonan</div>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                @if($permohonans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="border-0">Permohonan</th>
                                <th class="border-0">Jenis</th>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permohonans->take(10) as $permohonan)
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-primary">{{ $permohonan->nomor_permohonan }}</h6>
                                        <p class="mb-0 text-muted small">{{ Str::limit($permohonan->perihal, 40) }}</p>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-file-alt me-1"></i>
                                        {{ str_replace('_', ' ', ucwords($permohonan->jenis_layanan)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="fw-medium">{{ $permohonan->tanggal_pengajuan->format('d M Y') }}</div>
                                        <div class="text-muted">{{ $permohonan->tanggal_pengajuan->format('H:i') }}</div>
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
                                    @case('disetujui_pimpinan')
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

                                        @if($permohonan->status === 'selesai' && $permohonan->file_hasil)
                                        <a href="{{ route('masyarakat.download-hasil', $permohonan->id) }}"
                                            class="btn btn-outline-success"
                                            title="Download Hasil">
                                            <i class="fas fa-download"></i>
                                        </a>
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
                    <p class="text-muted mb-4">Anda belum mengajukan permohonan apapun</p>
                    <a href="{{ route('masyarakat.create-permohonan') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Ajukan Permohonan Pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detail Modals -->
@foreach($permohonans as $permohonan)
<div class="modal fade" id="detailModal{{ $permohonan->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
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
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-semibold text-primary mb-3">
                                <i class="fas fa-file-text me-2"></i>Informasi Permohonan
                            </h6>

                            <div class="mb-3">
                                <label class="small text-muted">Nomor Permohonan</label>
                                <div class="fw-semibold">{{ $permohonan->nomor_permohonan }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted">Jenis Layanan</label>
                                <div class="fw-semibold">{{ str_replace('_', ' ', ucwords($permohonan->jenis_layanan)) }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted">Perihal</label>
                                <div class="fw-semibold">{{ $permohonan->perihal }}</div>
                            </div>

                            @if($permohonan->keterangan)
                            <div class="mb-3">
                                <label class="small text-muted">Keterangan</label>
                                <div class="fw-semibold">{{ $permohonan->keterangan }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
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
                                    @case('disetujui_pimpinan')
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
                        </div>
                    </div>
                </div>

                @if($permohonan->catatan_petugas)
                <div class="mt-4">
                    <div class="alert alert-info border-0">
                        <h6 class="fw-semibold mb-2">
                            <i class="fas fa-comment-alt me-2"></i>Catatan dari Petugas
                        </h6>
                        <p class="mb-0">{{ $permohonan->catatan_petugas }}</p>
                    </div>
                </div>
                @endif

                @if($permohonan->file_persyaratan && count($permohonan->file_persyaratan) > 0)
                <div class="mt-4">
                    <h6 class="fw-semibold text-primary mb-3">
                        <i class="fas fa-paperclip me-2"></i>Dokumen yang Diupload
                    </h6>
                    <div class="row g-2">
                        @foreach($permohonan->file_persyaratan as $file)
                        <div class="col-md-6">
                            <div class="border rounded-3 p-2 d-flex align-items-center">
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                <span class="small">{{ $file }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($permohonan->status === 'selesai' && $permohonan->file_hasil)
                <div class="mt-4">
                    <div class="alert alert-success border-0">
                        <h6 class="fw-semibold mb-2">
                            <i class="fas fa-download me-2"></i>File Hasil Tersedia
                        </h6>
                        <p class="mb-3">Surat hasil permohonan Anda sudah siap untuk diunduh.</p>
                        <a href="{{ route('masyarakat.download-hasil', $permohonan->id) }}"
                            class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Download Hasil Surat
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    // Auto refresh notifications
    setInterval(function() {
        // You can add AJAX call here to refresh notification count
    }, 30000); // 30 seconds

    // Smooth animations for cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush