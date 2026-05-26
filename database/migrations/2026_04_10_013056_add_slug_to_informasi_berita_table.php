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
        Schema::table('informasi_berita', function (Blueprint $table) {
            $table->string('slug', 200)->nullable()->unique()->after('judul');
            $table->enum('status', ['draft', 'published'])->default('published')->after('isi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informasi_berita', function (Blueprint $table) {
            $table->dropColumn(['slug', 'status']);
        });
    }
};
