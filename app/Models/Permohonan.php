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
        'catatan_petugas',
        'processed_by',
        'file_persyaratan',
        'file_hasil',
        'tanggal_pengajuan',
        'tanggal_diproses',
        'tanggal_selesai',
    ];

    protected $casts = [
        'file_persyaratan' => 'array',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
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

        // Cari nomor terakhir untuk bulan dan tahun ini
        $lastPermohonan = self::whereYear('created_at', $year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastPermohonan) {
            // Extract nomor dari nomor permohonan terakhir
            $lastNomor = $lastPermohonan->nomor_permohonan;
            $parts = explode('-', $lastNomor);
            $lastNumber = intval(end($parts));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Generate nomor baru dengan retry logic
        do {
            $nomorPermohonan = 'SKP-' . $year . '-' . $month . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // Cek apakah nomor sudah ada
            $exists = self::where('nomor_permohonan', $nomorPermohonan)->exists();

            if ($exists) {
                $newNumber++; // Increment jika sudah ada
            }
        } while ($exists);

        return $nomorPermohonan;
    }
}
