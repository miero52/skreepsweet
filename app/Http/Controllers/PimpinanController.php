<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PimpinanController extends Controller
{
    // Dashboard Pimpinan
    public function dashboard()
    {
        // Statistik keseluruhan
        $stats = [
            'total_permohonan' => Permohonan::count(),
            'menunggu' => Permohonan::where('status', 'menunggu')->count(),
            'diproses' => Permohonan::where('status', 'diproses')->count(),
            'selesai' => Permohonan::where('status', 'selesai')->count(),
            'ditolak' => Permohonan::where('status', 'ditolak')->count(),
            'menunggu_approval' => Permohonan::where('approval_status', 'menunggu_approval')->count(),
            'disetujui' => Permohonan::where('approval_status', 'disetujui')->count(),
        ];

        // Statistik bulan ini
        $statsMonth = [
            'total' => Permohonan::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'selesai' => Permohonan::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where('status', 'selesai')
                ->count(),
        ];

        // Data untuk grafik (6 bulan terakhir)
        $chartData = $this->getChartData();

        // Permohonan yang perlu approval
        $needsApproval = Permohonan::with(['user', 'petugas'])
            ->where('status', 'diproses')
            ->where(function ($query) {
                $query->whereNull('approval_status')
                    ->orWhere('approval_status', 'menunggu_approval');
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Kinerja petugas
        $petugasPerformance = $this->getPetugasPerformance();

        return view('pimpinan.dashboard', compact(
            'stats',
            'statsMonth',
            'chartData',
            'needsApproval',
            'petugasPerformance'
        ));
    }

    // Monitoring semua permohonan (read-only)
    public function monitoring()
    {
        $permohonans = Permohonan::with(['user', 'petugas', 'pimpinan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pimpinan.monitoring', compact('permohonans'));
    }

    // Detail permohonan
    public function show($id)
    {
        $permohonan = Permohonan::with(['user', 'petugas', 'pimpinan'])->findOrFail($id);
        return view('pimpinan.show', compact('permohonan'));
    }

    public function updateApproval(Request $request, $id)
    {
        $request->validate([
            'approval_status' => 'required|in:disetujui,ditolak_pimpinan',
            'catatan_pimpinan' => 'nullable|string'
        ]);

        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->status !== 'diproses') {
            return back()->with('error', 'Permohonan belum diproses petugas.');
        }

        if (in_array($permohonan->approval_status, ['disetujui', 'ditolak_pimpinan'])) {
            return back()->with('error', 'Keputusan pimpinan sudah diberikan.');
        }

        // DATA UPDATE
        $updateData = [
            'approval_status' => $request->approval_status,
            'catatan_pimpinan' => $request->catatan_pimpinan,
            'approved_by' => auth()->id(),
            'tanggal_approval' => now(),
        ];

        // 👉 INI KUNCI UTAMA
        if ($request->approval_status === 'ditolak_pimpinan') {
            $updateData['status'] = 'ditolak';
        }

        $permohonan->update($updateData);

        return back()->with('success', 'Keputusan pimpinan berhasil disimpan.');
    }





    // Laporan PDF
    public function exportLaporan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
            'status' => 'nullable|in:menunggu,diproses,selesai,ditolak',
            'jenis' => 'nullable|in:surat_keterangan,surat_izin'
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $status = $request->status;
        $jenis = $request->jenis;

        $query = Permohonan::with('user')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan);

        if ($status) {
            $query->where('status', $status);
        }

        if ($jenis) {
            $query->where('jenis_layanan', $jenis);
        }

        $permohonans = $query->orderBy('created_at', 'asc')->get();

        $bulanNama = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $data = [
            'permohonans' => $permohonans,
            'bulan' => $bulanNama[$bulan],
            'tahun' => $tahun,
            'total' => $permohonans->count(),
            'stats' => [
                'menunggu' => $permohonans->where('status', 'menunggu')->count(),
                'diproses' => $permohonans->where('status', 'diproses')->count(),
                'selesai' => $permohonans->where('status', 'selesai')->count(),
                'ditolak' => $permohonans->where('status', 'ditolak')->count(),
            ],
            'filter_status' => $status,
            'filter_jenis' => $jenis,
            'generated_at' => Carbon::now()->format('d F Y, H:i:s'),
            'generated_by' => Auth::user()->name
        ];

        $pdf = Pdf::loadView('pimpinan.laporan-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = "Laporan_Permohonan_{$bulanNama[$bulan]}_{$tahun}.pdf";
        return $pdf->download($filename);
    }

    // Halaman laporan
    public function laporanPage()
    {
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $tahunList = [];
        $currentYear = date('Y');
        for ($i = $currentYear; $i >= $currentYear - 3; $i--) {
            $tahunList[$i] = $i;
        }

        return view('pimpinan.laporan', compact('bulanList', 'tahunList'));
    }


    // Helper: Get chart data for last 6 months
    private function getChartData()
    {
        $months = [];
        $data = [
            'labels' => [],
            'total' => [],
            'selesai' => [],
            'ditolak' => [],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $data['labels'][] = $date->format('M Y');

            $total = Permohonan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $selesai = Permohonan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', 'selesai')
                ->count();

            $ditolak = Permohonan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', 'ditolak')
                ->count();

            $data['total'][] = $total;
            $data['selesai'][] = $selesai;
            $data['ditolak'][] = $ditolak;
        }

        return $data;
    }

    // Helper: Get petugas performance
    private function getPetugasPerformance()
    {
        return User::where('role', 'petugas')
            ->withCount([
                'processedPermohonans as total_processed',
                'processedPermohonans as selesai' => function ($query) {
                    $query->where('status', 'selesai');
                },
            ])
            ->having('total_processed', '>', 0)
            ->orderByDesc('total_processed')
            ->limit(5)
            ->get()
            ->map(function ($petugas) {
                $petugas->success_rate = $petugas->total_processed > 0
                    ? round(($petugas->selesai / $petugas->total_processed) * 100, 1)
                    : 0;
                return $petugas;
            });
    }

    // Helper: Get petugas performance by period
    private function getPetugasPerformanceByPeriod($bulan, $tahun)
    {
        return User::where('role', 'petugas')
            ->withCount([
                'processedPermohonans as total' => function ($query) use ($bulan, $tahun) {
                    $query->whereYear('tanggal_diproses', $tahun)
                        ->whereMonth('tanggal_diproses', $bulan);
                },
                'processedPermohonans as selesai' => function ($query) use ($bulan, $tahun) {
                    $query->where('status', 'selesai')
                        ->whereYear('tanggal_diproses', $tahun)
                        ->whereMonth('tanggal_diproses', $bulan);
                }
            ])
            ->having('total', '>', 0)
            ->get();
    }
}
