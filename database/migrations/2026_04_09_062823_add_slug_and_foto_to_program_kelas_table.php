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
            $table->string('slug')->nullable()->unique()->after('nama_program');
            $table->string('foto')->nullable()->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_kelas', function (Blueprint $table) {
            $table->dropColumn(['slug', 'foto']);
        });
    }
};
