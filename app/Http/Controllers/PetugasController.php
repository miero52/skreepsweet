<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\StatusPermohonanUpdated;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class PetugasController extends Controller
{
    public function dashboard()
    {
        try {
            $permohonans = Permohonan::with('user')->orderBy('created_at', 'desc')->get();
            $stats = [
                'menunggu' => Permohonan::where('status', 'menunggu')->count(),
                'diproses' => Permohonan::where('status', 'diproses')->count(),
                'selesai' => Permohonan::where('status', 'selesai')->count(),
                'ditolak' => Permohonan::where('status', 'ditolak')->count(),
            ];

            return view('petugas.dashboard', compact('permohonans', 'stats'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showPermohonan($id)
    {
        $permohonan = Permohonan::with('user')->findOrFail($id);
        return view('petugas.show-permohonan', compact('permohonan'));
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak',
            'catatan_petugas' => 'nullable|string',
            'file_hasil' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
        ]);

        $permohonan = Permohonan::findOrFail($id);
        $oldStatus = $permohonan->status;

        // Handle file upload untuk status selesai
        $fileHasil = $permohonan->file_hasil;
        if ($request->hasFile('file_hasil')) {
            // Delete old file if exists
            if ($fileHasil && file_exists(public_path('uploads/hasil/' . $fileHasil))) {
                unlink(public_path('uploads/hasil/' . $fileHasil));
            }

            // Upload new file
            $file = $request->file('file_hasil');
            $filename = 'hasil_' . $permohonan->nomor_permohonan . '_' . time() . '.pdf';
            $file->move(public_path('uploads/hasil'), $filename);
            $fileHasil = $filename;
        }

        // Validate: status selesai harus ada file hasil
        if ($request->status === 'selesai' && !$fileHasil) {
            return redirect()->back()->with('error', 'File hasil surat wajib diupload untuk status "Selesai"');
        }

        $permohonan->update([
            'status' => $request->status,
            'catatan_petugas' => $request->catatan_petugas,
            'processed_by' => Auth::id(),
            'tanggal_diproses' => now(),
            'tanggal_selesai' => $request->status === 'selesai' ? now() : null,
            'file_hasil' => $fileHasil,
        ]);

        // Send notification
        if ($oldStatus !== $request->status) {
            try {
                $permohonan->user->notify(new StatusPermohonanUpdated($permohonan));
                $successMessage = 'Status berhasil diupdate dan notifikasi telah dikirim!';
            } catch (\Exception $e) {
                Log::error('Failed to send notification: ' . $e->getMessage());
                $successMessage = 'Status berhasil diupdate, namun gagal mengirim email.';
            }
        } else {
            $successMessage = 'Status berhasil diupdate!';
        }

        return redirect()->route('petugas.dashboard')->with('success', $successMessage);
    }

    public function laporanPage()
    {
        // Halaman untuk memilih filter laporan
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

        return view('petugas.laporan', compact('bulanList', 'tahunList'));
    }
    public function exportPDF(Request $request)
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

        // Query data berdasarkan filter
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

        // Data untuk laporan
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

        // Generate PDF
        $pdf = Pdf::loadView('petugas.laporan-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        // Filename
        $filename = "Laporan_Permohonan_{$bulanNama[$bulan]}_{$tahun}.pdf";

        return $pdf->download($filename);
    }
}
