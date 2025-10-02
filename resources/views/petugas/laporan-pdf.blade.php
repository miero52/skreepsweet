<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Permohonan {{ $bulan }} {{ $tahun }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }

        .subtitle {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }

        .stats-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        .stats-table th,
        .stats-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .stats-table th {
            background: #007bff;
            color: white;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: bold;
        }

        .status-menunggu {
            background: #fff3cd;
        }

        .status-diproses {
            background: #d1ecf1;
        }

        .status-selesai {
            background: #d4edda;
        }

        .status-ditolak {
            background: #f8d7da;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h2>KEMENTERIAN AGAMA PALEMBANG</h2>
        <div class="title">LAPORAN DATA PERMOHONAN</div>
        <div class="subtitle">Sistem Layanan Administrasi Publik (SILAP)</div>
        <div class="subtitle">Periode: {{ $bulan }} {{ $tahun }}</div>
    </div>

    <!-- Info Laporan -->
    <div class="info-box">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none;"><strong>Periode:</strong> {{ $bulan }} {{ $tahun }}</td>
                <td style="border: none;"><strong>Total Data:</strong> {{ $total }} permohonan</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Tanggal Generate:</strong> {{ $generated_at }}</td>
                <td style="border: none;"><strong>Dibuat oleh:</strong> {{ $generated_by }}</td>
            </tr>
            @if($filter_status)
            <tr>
                <td style="border: none;"><strong>Filter Status:</strong> {{ ucfirst($filter_status) }}</td>
                <td style="border: none;"></td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Statistik -->
    <h3>Ringkasan Statistik</h3>
    <table class="stats-table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            <tr class="status-menunggu">
                <td>Menunggu</td>
                <td>{{ $stats['menunggu'] }}</td>
                <td>{{ $total > 0 ? round(($stats['menunggu']/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr class="status-diproses">
                <td>Diproses</td>
                <td>{{ $stats['diproses'] }}</td>
                <td>{{ $total > 0 ? round(($stats['diproses']/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr class="status-selesai">
                <td>Selesai</td>
                <td>{{ $stats['selesai'] }}</td>
                <td>{{ $total > 0 ? round(($stats['selesai']/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr class="status-ditolak">
                <td>Ditolak</td>
                <td>{{ $stats['ditolak'] }}</td>
                <td>{{ $total > 0 ? round(($stats['ditolak']/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr style="background: #e9ecef; font-weight: bold;">
                <td>TOTAL</td>
                <td>{{ $total }}</td>
                <td>100%</td>
            </tr>
        </tbody>
    </table>

    <!-- Data Detail -->
    @if($permohonans->count() > 0)
    <div class="page-break">
        <h3>Data Detail Permohonan</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Nomor</th>
                    <th width="20%">Pemohon</th>
                    <th width="15%">Jenis</th>
                    <th width="25%">Perihal</th>
                    <th width="12%">Tanggal</th>
                    <th width="8%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permohonans as $index => $permohonan)
                <tr class="status-{{ $permohonan->status }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $permohonan->nomor_permohonan }}</td>
                    <td>{{ $permohonan->user->name }}</td>
                    <td>{{ str_replace('_', ' ', ucwords($permohonan->jenis_layanan)) }}</td>
                    <td>{{ Str::limit($permohonan->perihal, 40) }}</td>
                    <td>{{ $permohonan->created_at->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($permohonan->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; margin: 50px 0; color: #6c757d;">
            Tidak ada data permohonan untuk periode yang dipilih.
        </p>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>--- Laporan ini digenerate otomatis oleh Sistem SILAP ---</p>
            <p>Kementerian Agama Palembang | {{ $generated_at }}</p>
        </div>
</body>

</html>