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
        // Perbaiki: gunakan relationship yang benar
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
            'jenis_layanan' => 'required|in:surat_keterangan,surat_izin',
            'perihal' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'file_persyaratan.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $files = [];
        if ($request->hasFile('file_persyaratan')) {
            foreach ($request->file('file_persyaratan') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/persyaratan'), $filename);
                $files[] = $filename;
            }
        }

        Permohonan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => $request->jenis_layanan,
            'perihal' => $request->perihal,
            'keterangan' => $request->keterangan,
            'file_persyaratan' => $files,
            'status' => 'menunggu'
        ]);

        return redirect()->route('masyarakat.dashboard')->with('success', 'Permohonan berhasil diajukan!');
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
