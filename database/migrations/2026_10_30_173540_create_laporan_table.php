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
    Schema::create('laporan', function (Blueprint $table) {
        $table->id();

        $table->foreignId('pelapor_id')
            ->constrained('users')
            ->restrictOnDelete();

        $table->foreignId('wilayah_id')
            ->constrained('wilayah')
            ->restrictOnDelete();

        $table->string('judul', 150);
        $table->text('deskripsi');
        $table->text('detail_lokasi');

        $table->decimal('latitude', 10, 7);
        $table->decimal('longitude', 10, 7);

        $table->dateTime('waktu_kejadian');
        $table->string('bukti_path')->nullable();

        $table->enum('status', [
            'baru',
            'perlu_perbaikan',
            'perlu_diteruskan',
            'diteruskan',
            'selesai',
        ])->default('baru');

        $table->timestamps();
    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
