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
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id('id_transaction');
                $table->date('tanggal');
                $table->string('jenis', 10);
                $table->decimal('nominal', 15, 2);
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('cascade');
            });
        } else {
            Schema::table('transactions', function (Blueprint $table) {
                if (!Schema::hasColumn('transactions', 'id_user_siswa')) {
                    $table->unsignedBigInteger('id_user_siswa')->nullable()->after('keterangan');
                    // Add foreign key if necessary
                }
                if (!Schema::hasColumn('transactions', 'id_user')) {
                    $table->foreignId('id_user')->nullable()->after('keterangan')->constrained('users', 'id_user')->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
