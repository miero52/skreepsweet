<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\StatusPermohonanUpdated;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\User;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $permohonans = Permohonan::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'menunggu' => Permohonan::where('status', 'menunggu')->count(),

            'diproses' => Permohonan::where('status', 'diproses')->count(),

            'menunggu_approval' => Permohonan::where('approval_status', 'menunggu_approval')->count(),

            'disetujui_pimpinan' => Permohonan::where('approval_status', 'disetujui')->count(),

            'selesai' => Permohonan::where('status', 'selesai')->count(),

            'ditolak' => Permohonan::where(function ($q) {
                $q->where('status', 'ditolak')
                    ->orWhere('approval_status', 'ditolak_pimpinan');
            })->count(),
        ];

        return view('petugas.dashboard', compact('permohonans', 'stats'));
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
            'file_hasil' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $permohonan = Permohonan::findOrFail($id);
        $oldStatus = $permohonan->status;

        /*
    |--------------------------------------------------------------------------
    | VALIDASI ALUR STATUS (WAJIB & BERURUTAN)
    |--------------------------------------------------------------------------
    */

        // 1. Dari MENUNGGU hanya boleh ke DIPROSES atau DITOLAK
        if (
            $permohonan->status === 'menunggu' &&
            !in_array($request->status, ['diproses', 'ditolak'])
        ) {
            return redirect()->back()->with(
                'error',
                'Permohonan harus diverifikasi terlebih dahulu.'
            );
        }

        // 2. Admin tidak boleh menyelesaikan tanpa persetujuan pimpinan
        if (
            $request->status === 'selesai' &&
            $permohonan->approval_status !== 'disetujui'
        ) {
            return redirect()->back()->with(
                'error',
                'Permohonan harus disetujui pimpinan terlebih dahulu.'
            );
        }

        // 3. Jika sudah ditolak pimpinan, tidak boleh diproses lagi
        if ($permohonan->approval_status === 'ditolak_pimpinan') {
            return redirect()->back()->with(
                'error',
                'Permohonan telah ditolak pimpinan dan tidak dapat diproses.'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | HANDLE FILE HASIL (KHUSUS STATUS SELESAI)
    |--------------------------------------------------------------------------
    */

        $fileHasil = $permohonan->file_hasil;

        if ($request->hasFile('file_hasil')) {
            if ($fileHasil && file_exists(public_path('uploads/hasil/' . $fileHasil))) {
                unlink(public_path('uploads/hasil/' . $fileHasil));
            }

            $file = $request->file('file_hasil');
            $filename = 'hasil_' . $permohonan->nomor_permohonan . '_' . time() . '.pdf';
            $file->move(public_path('uploads/hasil'), $filename);
            $fileHasil = $filename;
        }

        // Status selesai WAJIB upload file
        if ($request->status === 'selesai' && !$fileHasil) {
            return redirect()->back()->with(
                'error',
                'File hasil surat wajib diupload untuk status "Selesai".'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | UPDATE DATA PERMOHONAN
    |--------------------------------------------------------------------------
    */

        $updateData = [
            'status' => $request->status,
            'catatan_petugas' => $request->catatan_petugas,
            'processed_by' => Auth::id(),
            'tanggal_diproses' => now(),
            'file_hasil' => $fileHasil,
        ];

        // Jika diproses → menunggu approval pimpinan
        if ($request->status === 'diproses') {
            $updateData['approval_status'] = 'menunggu_approval';
        }

        // Jika selesai → set tanggal selesai
        if ($request->status === 'selesai') {
            $updateData['tanggal_selesai'] = now();
        }

        $permohonan->update($updateData);

        /*
    |--------------------------------------------------------------------------
    | NOTIFIKASI KE MASYARAKAT
    |--------------------------------------------------------------------------
    */

        if ($oldStatus !== $request->status) {
            try {
                $permohonan->user->notify(
                    new StatusPermohonanUpdated($permohonan)
                );
                $successMessage = 'Status berhasil diperbarui dan notifikasi dikirim.';
            } catch (\Exception $e) {
                Log::error('Gagal kirim notifikasi: ' . $e->getMessage());
                $successMessage = 'Status diperbarui, namun notifikasi gagal dikirim.';
            }
        } else {
            $successMessage = 'Status berhasil diperbarui.';
        }

        return redirect()
            ->route('petugas.dashboard')
            ->with('success', $successMessage);
    }


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

        $pdf = Pdf::loadView('petugas.laporan-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = "Laporan_Permohonan_{$bulanNama[$bulan]}_{$tahun}.pdf";
        return $pdf->download($filename);
    }

    public function indexUsers()
    {
        $users = User::where('role', 'masyarakat')->paginate(10);
        return view('petugas.users.index', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('petugas.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'required|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('petugas.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('petugas.users.index')->with('success', 'Data pengguna berhasil dihapus.');
    }
}
