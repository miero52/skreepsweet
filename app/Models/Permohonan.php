<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Permohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_permohonan',
        'jenis_layanan',
        'perihal',
        'keterangan',
        'status',
        'approval_status',
        'catatan_petugas',
        'catatan_pimpinan',
        'processed_by',
        'approved_by',
        'file_persyaratan',
        'file_hasil',
        'tanggal_pengajuan',
        'tanggal_diproses',
        'tanggal_selesai',
        'tanggal_approval',
        'jenis_surat_detail',
    ];

    protected $casts = [
        'file_persyaratan' => 'array',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_approval' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function pimpinan()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Auto generate nomor permohonan
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permohonan) {
            $permohonan->nomor_permohonan = self::generateNomorPermohonan();
        });
    }

    public static function generateNomorPermohonan()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->format('m');

        $lastPermohonan = self::whereYear('created_at', $year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastPermohonan) {
            $lastNomor = $lastPermohonan->nomor_permohonan;
            $parts = explode('-', $lastNomor);
            $lastNumber = intval(end($parts));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        do {
            $nomorPermohonan = 'SKP-' . $year . '-' . $month . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $exists = self::where('nomor_permohonan', $nomorPermohonan)->exists();
            if ($exists) {
                $newNumber++;
            }
        } while ($exists);

        return $nomorPermohonan;
    }

    // Helper method untuk cek apakah butuh approval
    public function needsApproval()
    {
        return $this->status === 'diverifikasi'
            && $this->approval_status === 'menunggu_approval';
    }

    public function isVerified()
    {
        return $this->status === 'diverifikasi';
    }

    public function isApproved()
    {
        return $this->approval_status === 'disetujui';
    }


    // Helper method untuk status badge
    public function getApprovalBadge()
    {
        if ($this->approval_status === 'disetujui') {
            return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Disetujui</span>';
        } elseif ($this->approval_status === 'ditolak_pimpinan') {
            return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Ditolak Pimpinan</span>';
        } elseif ($this->approval_status === 'menunggu_approval') {
            return '<span class="badge bg-warning"><i class="fas fa-clock"></i> Menunggu Approval</span>';
        }
        return '<span class="badge bg-secondary">Belum Perlu Approval</span>';
    }
}
