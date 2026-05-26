<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran', 'id_user')) {
                $table->integer('id_user')->nullable()->after('id_pendaftaran');
            }
            if (!Schema::hasColumn('pendaftaran', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pendaftaran', 'status_pembayaran')) {
                $table->string('status_pembayaran')->nullable()->after('metode_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['id_user', 'metode_pembayaran', 'status_pembayaran']);
        });
    }
};
