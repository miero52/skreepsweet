<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Permohonan {{ $bulan }} {{ $tahun }}</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .kop-surat table {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-logo {
            width: 80px;
            height: auto;
        }

        .judul-instansi {
            font-size: 16px;
            font-weight: bold;
        }

        .alamat-instansi {
            font-size: 12px;
        }

        .judul-laporan {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .judul-laporan h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        .stats-table,
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .stats-table th,
        .stats-table td,
        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .stats-table th {
            background: #e4e4e4;
            font-weight: bold;
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            font-size: 11px;
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <!-- === HEADER SURAT RESMI === -->
    <div class="kop-surat">
        <table>
            <tr>
                <td width="15%" align="center">
                    <img src="{{ public_path('images/kemenag.png') }}" width="80">

                </td>
                <td width="85%" align="center">
                    <div class="judul-instansi">
                        KEMENTERIAN AGAMA REPUBLIK INDONESIA<br>
                        KANTOR KEMENTERIAN AGAMA KOTA PALEMBANG
                    </div>
                    <div class="alamat-instansi">
                        Jalan Jenderal Ahmad Yani 14 Ulu Palembang 30264<br>
                        Telepon/Faksimile (0711) 511117 • Email: kotapalembang@kemenag.go.id
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- === JUDUL LAPORAN === -->
    <div class="judul-laporan">
        <h2>LAPORAN DATA PERMOHONAN</h2>
        <div>Sistem Informsi Layanan Administrasi Publik (SILAP)</div>
        <div><strong>Periode: {{ $bulan }} {{ $tahun }}</strong></div>
    </div>

    <!-- === INFORMASI LAPORAN === -->
    <div class="info-box">
        <table width="100%">
            <tr>
                <td><strong>Periode:</strong> {{ $bulan }} {{ $tahun }}</td>
                <td><strong>Total Data:</strong> {{ $total }} permohonan</td>
            </tr>
            <tr>
                <td><strong>Tanggal Generate:</strong> {{ $generated_at }}</td>
                <td><strong>Dibuat oleh:</strong> {{ $generated_by }}</td>
            </tr>
            @if($filter_status)
            <tr>
                <td><strong>Filter Status:</strong> {{ ucfirst($filter_status) }}</td>
                <td></td>
            </tr>
            @endif
        </table>
    </div>

    <!-- === RINGKASAN STATISTIK === -->
    <h3 style="margin-bottom: 5px;">Ringkasan Statistik</h3>
    <table class="stats-table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Menunggu</td>
                <td>{{ $stats['menunggu'] }}</td>
                <td>{{ $total > 0 ? round(($stats['menunggu']/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Diproses</td>
                <td>{{ $stats['diproses'] }}</td>
                <td>{{ $total > 0 ? round(($stats['diproses']/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Selesai</td>
                <td>{{ $stats['selesai'] }}</td>
                <td>{{ $total > 0 ? round(($stats['selesai']/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Ditolak</td>
                <td>{{ $stats['ditolak'] }}</td>
                <td>{{ $total > 0 ? round(($stats['ditolak']/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr style="font-weight: bold;">
                <td>TOTAL</td>
                <td>{{ $total }}</td>
                <td>100%</td>
            </tr>
        </tbody>
    </table>

    <!-- === DATA DETAIL PERMOHONAN === -->
    @if($permohonans->count() > 0)
    <div class="page-break"></div>
    <h3>Data Detail Permohonan</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor</th>
                <th>Pemohon</th>
                <th>Jenis</th>
                <th>Perihal</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permohonans as $index => $permohonan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $permohonan->nomor_permohonan }}</td>
                <td>{{ $permohonan->user->name }}</td>
                <td>{{ str_replace('_',' ', ucwords($permohonan->jenis_layanan)) }}</td>
                <td>{{ Str::limit($permohonan->perihal, 40) }}</td>
                <td>{{ $permohonan->created_at->format('d/m/Y') }}</td>
                <td>{{ ucfirst($permohonan->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    <p style="text-align: center; margin: 50px 0;">
        Tidak ada data permohonan untuk periode yang dipilih.
    </p>
    @endif

    <!-- === FOOTER === -->
    <div class="footer">
        <p><i>Laporan ini digenerate otomatis oleh Sistem SILAP</i></p>
        <p>Kementerian Agama Kota Palembang</p>
    </div>

</body>

</html>