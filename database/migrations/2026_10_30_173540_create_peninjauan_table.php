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
    Schema::create('peninjauan', function (Blueprint $table) {
        $table->id();

        $table->foreignId('laporan_id')
            ->constrained('laporan')
            ->restrictOnDelete();

        $table->foreignId('pemantau_id')
            ->constrained('users')
            ->restrictOnDelete();

        $table->enum('hasil', [
            'perlu_perbaikan',
            'perlu_diteruskan',
            'selesai',
        ]);

        $table->text('catatan');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peninjauan');
    }
};
