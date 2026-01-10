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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    Review & Approval Permohonan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="alert alert-light border">
                    <p><strong>No Permohonan:</strong> {{ $permohonan->nomor_permohonan }}</p>
                    <p><strong>Pemohon:</strong> {{ $permohonan->user->name }}</p>
                    <p><strong>Perihal:</strong> {{ $permohonan->perihal }}</p>
                    <p><strong>Petugas:</strong> {{ $permohonan->petugas->name ?? '-' }}</p>

                    @if($permohonan->catatan_petugas)
                    <p><strong>Catatan Petugas:</strong> {{ $permohonan->catatan_petugas }}</p>
                    @endif
                </div>

                <!-- JIKA SUDAH ADA KEPUTUSAN -->
                @if(in_array($permohonan->approval_status, ['disetujui','ditolak_pimpinan']))
                <div class="alert alert-info">
                    Permohonan ini sudah diberi keputusan.
                </div>
                @else
                <!-- FORM APPROVAL (SATU-SATUNYA) -->
                <form method="POST" action="{{ route('pimpinan.update-approval', $permohonan->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keputusan</label>
                        <select name="approval_status" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="disetujui">Setujui</option>
                            <option value="ditolak_pimpinan">Tolak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Pimpinan</label>
                        <textarea name="catatan_pimpinan" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Simpan Keputusan
                        </button>
                    </div>
                </form>
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