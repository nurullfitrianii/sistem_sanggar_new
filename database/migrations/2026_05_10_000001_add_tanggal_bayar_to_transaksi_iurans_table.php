<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_iurans', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_iurans', 'tanggal_bayar')) {
                $table->timestamp('tanggal_bayar')->nullable()->after('bukti_bayar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_iurans', function (Blueprint $table) {
            $table->dropColumn('tanggal_bayar');
        });
    }
};
