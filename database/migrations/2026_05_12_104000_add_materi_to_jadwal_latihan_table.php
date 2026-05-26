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
        Schema::table('jadwal_latihan', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwal_latihan', 'materi')) {
                $table->text('materi')->nullable()->after('lokasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_latihan', function (Blueprint $table) {
            $table->dropColumn('materi');
        });
    }
};
