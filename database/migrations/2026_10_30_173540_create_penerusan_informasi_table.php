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
    Schema::create('penerusan_informasi', function (Blueprint $table) {
        $table->id();

        $table->foreignId('laporan_id')
            ->constrained('laporan')
            ->restrictOnDelete();

        $table->foreignId('koordinator_id')
            ->constrained('users')
            ->restrictOnDelete();

        $table->string('pihak_tujuan', 150);
        $table->dateTime('waktu_penerusan');
        $table->text('catatan');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerusan_informasi');
    }
};
