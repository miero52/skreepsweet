<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('permohonans', function (Blueprint $table) {
            if (!Schema::hasColumn('permohonans', 'file_hasil')) {
                $table->string('file_hasil')->nullable()->after('file_persyaratan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            //
        });
    }
};
