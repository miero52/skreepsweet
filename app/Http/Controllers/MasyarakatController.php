<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use Illuminate\Support\Facades\Auth;

class MasyarakatController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $permohonans = Permohonan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('masyarakat.dashboard', compact('permohonans'));
    }

    public function createPermohonan()
    {
        return view('masyarakat.create-permohonan');
    }

    public function storePermohonan(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string',
            'perihal' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'file_persyaratan.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ], [
            'jenis_surat.required' => 'Jenis surat harus dipilih',
            'perihal.required' => 'Perihal wajib diisi',
            'file_persyaratan.*.required' => 'Minimal upload 1 dokumen',
            'file_persyaratan.*.mimes' => 'File harus berformat PDF, JPG, atau PNG',
            'file_persyaratan.*.max' => 'Ukuran file maksimal 2MB'
        ]);

        $files = [];
        if ($request->hasFile('file_persyaratan')) {
            foreach ($request->file('file_persyaratan') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/persyaratan'), $filename);
                $files[] = $filename;
            }
        }

        // Mapping jenis surat ke label yang readable
        $jenisLabels = [
            'izin_penelitian' => 'Surat Izin Penelitian',
            'rohaniwan_islam' => 'Surat Permohonan Rohaniwan Islam',
            'rohaniwan_buddha' => 'Surat Permohonan Rohaniwan Buddha',
            'rohaniwan_kristen' => 'Surat Permohonan Rohaniwan Kristen',
            'pengukuran_kiblat' => 'Surat Pengukuran Arah Kiblat',
            'izin_majelis' => 'Surat Izin Majelis',
            'izin_tpq' => 'Permohonan Izin Pendirian TPQ',
        ];

        $jenisSuratLabel = $jenisLabels[$request->jenis_surat] ?? 'Surat Permohonan';

        Permohonan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'surat_izin', // FIX: Gunakan nilai yang ada di enum
            'perihal' => $jenisSuratLabel . ' - ' . $request->perihal,
            'keterangan' => "Jenis Surat: " . $jenisSuratLabel . "\n\nDetail: " . $request->jenis_surat . "\n\n" . ($request->keterangan ?? ''),
            'file_persyaratan' => $files,
            'status' => 'menunggu'
        ]);

        return redirect()->route('masyarakat.dashboard')
            ->with('success', 'Permohonan berhasil diajukan! Silakan tunggu proses verifikasi dari petugas.');
    }

    public function downloadHasil($id)
    {
        $permohonan = Permohonan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->first();

        if (!$permohonan || !$permohonan->file_hasil) {
            return redirect()->back()->with('error', 'File tidak ditemukan atau permohonan belum selesai.');
        }

        $filePath = public_path('uploads/hasil/' . $permohonan->file_hasil);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        $downloadName = 'Hasil_' . $permohonan->nomor_permohonan . '.pdf';

        return response()->download($filePath, $downloadName);
    }
}
