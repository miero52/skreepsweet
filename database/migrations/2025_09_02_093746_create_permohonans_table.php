<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Data Permohonan
            $table->string('nomor_permohonan')->unique();
            $table->enum('jenis_layanan', ['surat_keterangan', 'surat_izin']);
            $table->string('perihal');
            $table->text('keterangan')->nullable();

            // Status & Tracking
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->text('catatan_petugas')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');

            // File Management
            $table->json('file_persyaratan')->nullable();
            $table->string('file_hasil')->nullable();

            // Timestamps
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->timestamp('tanggal_diproses')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonans');
    }
};
