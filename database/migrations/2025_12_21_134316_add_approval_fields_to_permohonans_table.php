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
        Schema::table('permohonans', function (Blueprint $table) {
            // Status approval dari pimpinan
            $table->enum('approval_status', ['menunggu_approval', 'disetujui', 'ditolak_pimpinan'])
                ->nullable()
                ->after('status');

            // ID pimpinan yang approve
            $table->foreignId('approved_by')
                ->nullable()
                ->after('processed_by')
                ->constrained('users')
                ->onDelete('set null');

            // Catatan dari pimpinan
            $table->text('catatan_pimpinan')->nullable()->after('catatan_petugas');

            // Tanggal approval
            $table->timestamp('tanggal_approval')->nullable()->after('tanggal_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'approval_status',
                'approved_by',
                'catatan_pimpinan',
                'tanggal_approval'
            ]);
        });
    }
};
