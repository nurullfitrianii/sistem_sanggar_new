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
        Schema::table('sanggar', function (Blueprint $table) {
            $table->date('pendaftaran_dibuka')->nullable();
            $table->date('pendaftaran_ditutup')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanggar', function (Blueprint $table) {
            $table->dropColumn(['pendaftaran_dibuka', 'pendaftaran_ditutup']);
        });
    }
};
