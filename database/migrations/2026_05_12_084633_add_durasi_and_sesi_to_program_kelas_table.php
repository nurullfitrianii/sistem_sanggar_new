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
        Schema::table('program_kelas', function (Blueprint $table) {
            $table->string('durasi')->nullable()->after('biaya');
            $table->string('jumlah_sesi')->nullable()->after('durasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_kelas', function (Blueprint $table) {
            $table->dropColumn(['durasi', 'jumlah_sesi']);
        });
    }
};
