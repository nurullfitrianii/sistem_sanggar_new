<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('transaksi_iurans', function (Blueprint $table) {
        $table->id();

        // Menggunakan integer() biasa karena kolom id_user di tabel users adalah int(11) (signed)
        $table->integer('user_id');

        $table->enum('tipe_iuran', ['mingguan', 'bulanan']);
        $table->enum('metode_pembayaran', ['tunai', 'transfer']);
        $table->integer('jumlah_bayar'); 
        $table->string('bukti_bayar')->nullable();
        $table->enum('status', ['pending', 'valid', 'ditolak'])->default('pending');
        $table->timestamps();

        // Foreign key mengarah ke kolom 'id_user', BUKAN 'id'
        $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('transaksi_iurans');
    }
};
