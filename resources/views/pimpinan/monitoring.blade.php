@extends('layouts.app')

@section('title', 'Monitoring Permohonan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            <i class="fas fa-list-alt text-primary me-2"></i>
                            Monitoring Semua Permohonan
                        </h5>
                        <small class="text-muted">Lihat dan pantau semua permohonan dari masyarakat</small>
                    </div>
                    <a href="{{ route('pimpinan.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nomor</th>
                                <th>Pemohon</th>
                                <th>Jenis & Perihal</th>
                                <th>Petugas</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permohonans as $permohonan)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-primary">{{ $permohonan->nomor_permohonan }}</span>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0 fw-medium">{{ $permohonan->user->name }}</h6>
                                        <small class="text-muted">{{ $permohonan->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge bg-light text-dark border mb-1">
                                            {{ str_replace('_', ' ', ucwords($permohonan->jenis_layanan)) }}
                                        </span>
                                        <div class="small">{{ Str::limit($permohonan->perihal, 30) }}</div>
                                    </div>
                                </td>
                                <td>
                                    {{ $permohonan->petugas->name ?? '-' }}
                                </td>
                                <td>
                                    @switch($permohonan->status)
                                    @case('menunggu')
                                    <span class="badge bg-warning">Menunggu</span>
                                    @break
                                    @case('diproses')
                                    <span class="badge bg-info">Diproses</span>
                                    @break
                                    @case('selesai')
                                    <span class="badge bg-success">Selesai</span>
                                    @break
                                    @case('ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @break
                                    @endswitch
                                </td>
                                <td>
                                    @if($permohonan->approval_status)
                                    @switch($permohonan->approval_status)
                                    @case('menunggu_approval')
                                    <span class="badge bg-warning">Menunggu</span>
                                    @break
                                    @case('disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                    @break
                                    @case('ditolak_pimpinan')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @break
                                    @endswitch
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        {{ $permohonan->created_at->format('d/m/Y') }}
                                        <div class="text-muted">{{ $permohonan->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $permohonan->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data permohonan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($permohonans->hasPages())
            <div class="card-footer">
                {{ $permohonans->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Detail Modals -->
@foreach($permohonans as $permohonan)
<div class="modal fade" id="detailModal{{ $permohonan->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Permohonan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small text-muted">Nomor Permohonan</label>
                        <div class="fw-semibold">{{ $permohonan->nomor_permohonan }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted">Status</label>
                        <div class="fw-semibold">{{ ucfirst($permohonan->status) }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted">Pemohon</label>
                        <div class="fw-semibold">{{ $permohonan->user->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted">Petugas</label>
                        <div class="fw-semibold">{{ $permohonan->petugas->name ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <label class="small text-muted">Perihal</label>
                        <div class="fw-semibold">{{ $permohonan->perihal }}</div>
                    </div>
                    @if($permohonan->catatan_petugas)
                    <div class="col-12">
                        <label class="small text-muted">Catatan Petugas</label>
                        <div class="alert alert-info mb-0">{{ $permohonan->catatan_petugas }}</div>
                    </div>
                    @endif
                    @if($permohonan->catatan_pimpinan)
                    <div class="col-12">
                        <label class="small text-muted">Catatan Pimpinan</label>
                        <div class="alert alert-warning mb-0">{{ $permohonan->catatan_pimpinan }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection