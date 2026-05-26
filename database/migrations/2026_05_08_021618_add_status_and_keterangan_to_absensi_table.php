<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Already handled manually or via previous attempts
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            if (Schema::hasColumn('absensi', 'keterangan')) {
                $table->dropColumn(['keterangan']);
            }
        });

        DB::statement("ALTER TABLE absensi MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'Hadir'");
    }
};
