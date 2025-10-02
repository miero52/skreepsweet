@extends('layouts.app')

@section('title', 'Export Laporan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title fw-bold text-dark mb-1">
                    <i class="fas fa-file-export text-primary me-2"></i>
                    Export Laporan PDF
                </h5>
                <small class="text-muted">Pilih periode dan filter untuk generate laporan</small>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('petugas.export-pdf') }}">
                    <div class="row g-3">
                        <!-- Periode -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bulan <span class="text-danger">*</span></label>
                            <select name="bulan" class="form-select" required>
                                <option value="">Pilih Bulan</option>
                                @foreach($bulanList as $key => $nama)
                                <option value="{{ $key }}" {{ $key == date('n') ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <select name="tahun" class="form-select" required>
                                <option value="">Pilih Tahun</option>
                                @foreach($tahunList as $key => $nama)
                                <option value="{{ $key }}" {{ $key == date('Y') ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Opsional -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status (Opsional)</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Layanan (Opsional)</label>
                            <select name="jenis" class="form-select">
                                <option value="">Semua Jenis</option>
                                <option value="surat_keterangan">Surat Keterangan</option>
                                <option value="surat_izin">Surat Izin</option>
                            </select>
                        </div>

                        <!-- Info -->
                        <div class="col-12">
                            <div class="alert alert-info border-0">
                                <h6 class="fw-semibold mb-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informasi Laporan
                                </h6>
                                <ul class="list-unstyled mb-0 small">
                                    <li><i class="fas fa-check text-success me-2"></i>Format: PDF siap cetak</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Termasuk statistik dan grafik</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Header resmi Kemenag Palembang</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Data detail setiap permohonan</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('petugas.dashboard') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-file-pdf me-2"></i>
                                    Generate PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection