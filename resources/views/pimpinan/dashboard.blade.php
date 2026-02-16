@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')

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
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <!-- Monitoring diarahkan ke dashboard sendiri -->
                            <a href="{{ route('pimpinan.monitoring') }}"
                                class="btn btn-outline-primary d-flex align-items-center gap-2 px-3">
                                <i class="fas fa-list"></i>
                                <span>Monitoring Permohonan</span>
                            </a>

                            <a href="{{ route('pimpinan.laporan') }}"
                                class="btn btn-outline-danger d-flex align-items-center gap-2 px-3">
                                <i class="fas fa-file-pdf"></i>
                                <span>Export Laporan</span>
                            </a>

                            <button class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3"
                                onclick="window.location.reload()">
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

<div class="row g-4">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-bold text-dark mb-1">
                    <i class="fas fa-chart-line text-primary me-2"></i>
                    Statistik 6 Bulan Terakhir
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statsChart" height="110"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- PERMOHONAN MENUNGGU APPROVAL -->
@if($needsApproval->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Permohonan Menunggu Approval
                    </h5>
                    <span class="badge bg-warning">{{ $needsApproval->count() }} Permohonan</span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nomor</th>
                                <th>Pemohon</th>
                                <th>Perihal</th>
                                <th>Petugas</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($needsApproval as $permohonan)
                            <tr>
                                <td class="fw-semibold text-primary">
                                    {{ $permohonan->nomor_permohonan }}
                                </td>
                                <td>{{ $permohonan->user->name }}</td>
                                <td>{{ Str::limit($permohonan->perihal, 40) }}</td>
                                <td>{{ $permohonan->petugas->name ?? '-' }}</td>
                                <td>{{ $permohonan->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#approvalModal{{ $permohonan->id }}">
                                        Review
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL APPROVAL -->
@foreach($needsApproval as $permohonan)
<div class="modal fade" id="approvalModal{{ $permohonan->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-gradient text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Review & Approval Permohonan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Detail Permohonan Lengkap -->
                <div class="row g-4 mb-4">
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

                            <!-- File Persyaratan -->
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

                    <div class="col-md-4">
                        <div class="border rounded-3 p-4 h-100">
                            <h6 class="fw-semibold text-success mb-3">
                                <i class="fas fa-tasks me-2"></i>Status & Timeline
                            </h6>

                            <div class="mb-3">
                                <label class="small text-muted">Status Saat Ini</label>
                                <div>
                                    @switch($permohonan->status)
                                    @case('diproses')
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-sync-alt me-1"></i> Sedang Diproses
                                    </span>
                                    @break
                                    @default
                                    <span class="badge bg-secondary fs-6">
                                        {{ ucfirst($permohonan->status) }}
                                    </span>
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

                            <div class="mb-3">
                                <label class="small text-muted">Diproses Oleh</label>
                                <div class="fw-semibold">{{ $permohonan->petugas->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM APPROVAL -->
                @if(in_array($permohonan->approval_status, ['disetujui','ditolak_pimpinan']))
                <div class="alert alert-info border-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Permohonan ini sudah diberi keputusan.
                </div>
                @else
                <div class="border-top pt-4">
                    <h6 class="fw-semibold text-dark mb-3">
                        <i class="fas fa-gavel me-2"></i>Form Keputusan Pimpinan
                    </h6>
                    <form method="POST" action="{{ route('pimpinan.update-approval', $permohonan->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Keputusan <span class="text-danger">*</span></label>
                                <select name="approval_status" class="form-select" required>
                                    <option value="">-- Pilih Keputusan --</option>
                                    <option value="disetujui">✓ Setujui Permohonan</option>
                                    <option value="ditolak_pimpinan">✗ Tolak Permohonan</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Catatan Pimpinan</label>
                                <textarea name="catatan_pimpinan"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Tambahkan catatan atau alasan keputusan..."></textarea>
                                <small class="text-muted">Catatan akan dikirim ke petugas dan pemohon</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i>Simpan Keputusan
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);

    new Chart(document.getElementById('statsChart'), {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                    label: 'Total',
                    data: chartData.total,
                    tension: 0.4
                },
                {
                    label: 'Selesai',
                    data: chartData.selesai,
                    tension: 0.4
                },
                {
                    label: 'Ditolak',
                    data: chartData.ditolak,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush