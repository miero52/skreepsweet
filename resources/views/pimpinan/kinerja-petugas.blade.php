@extends('layouts.app')

@section('title', 'Monitoring Kinerja Petugas')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            <i class="fas fa-chart-bar text-success me-2"></i>
                            Monitoring Kinerja Petugas
                        </h5>
                        <small class="text-muted">Evaluasi performa dan produktivitas petugas</small>
                    </div>
                    <a href="{{ route('pimpinan.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($petugas as $index => $ptgs)
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-user-tie fa-2x text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1">{{ $ptgs->name }}</h5>
                        <p class="text-muted mb-0">{{ $ptgs->email }}</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $index === 0 ? 'warning' : 'light' }} text-dark">
                            #{{ $index + 1 }}
                        </span>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="fw-bold text-primary mb-1">{{ $ptgs->total_processed }}</h3>
                            <small class="text-muted">Total Diproses</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="fw-bold text-success mb-1">{{ $ptgs->selesai }}</h3>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                            <h3 class="fw-bold text-danger mb-1">{{ $ptgs->ditolak }}</h3>
                            <small class="text-muted">Ditolak</small>
                        </div>
                    </div>
                </div>

                <!-- Success Rate Progress Bar -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-semibold">Success Rate</span>
                        <span class="fw-bold text-{{ $ptgs->total_processed > 0 ? (($ptgs->selesai / $ptgs->total_processed) * 100 >= 80 ? 'success' : 'warning') : 'secondary' }}">
                            {{ $ptgs->total_processed > 0 ? number_format(($ptgs->selesai / $ptgs->total_processed) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-{{ $ptgs->total_processed > 0 ? (($ptgs->selesai / $ptgs->total_processed) * 100 >= 80 ? 'success' : 'warning') : 'secondary' }}"
                            style="width: {{ $ptgs->total_processed > 0 ? ($ptgs->selesai / $ptgs->total_processed) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <!-- Status Breakdown -->
                <div class="row g-2 text-center small">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="fw-semibold">Dalam Proses</div>
                            <div class="text-muted">{{ $ptgs->total_processed - $ptgs->selesai - $ptgs->ditolak }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="fw-semibold">Rejection Rate</div>
                            <div class="text-muted">
                                {{ $ptgs->total_processed > 0 ? number_format(($ptgs->ditolak / $ptgs->total_processed) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-users-slash fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Belum Ada Data Kinerja Petugas</h5>
        </div>
    </div>
    @endforelse
</div>

@if($petugas->count() > 0)
<!-- Summary Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary bg-opacity-10">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="fas fa-chart-pie text-primary me-2"></i>
                    Ringkasan Keseluruhan
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h3 class="fw-bold text-primary">{{ $petugas->count() }}</h3>
                        <p class="text-muted mb-0">Total Petugas</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="fw-bold text-info">{{ $petugas->sum('total_processed') }}</h3>
                        <p class="text-muted mb-0">Total Permohonan</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="fw-bold text-success">{{ $petugas->sum('selesai') }}</h3>
                        <p class="text-muted mb-0">Berhasil Diselesaikan</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="fw-bold text-warning">
                            {{ $petugas->sum('total_processed') > 0 ? number_format(($petugas->sum('selesai') / $petugas->sum('total_processed')) * 100, 1) : 0 }}%
                        </h3>
                        <p class="text-muted mb-0">Rata-rata Success Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection