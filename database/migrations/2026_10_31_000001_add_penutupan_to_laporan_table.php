<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->text('catatan_penutupan')->nullable();
            $table->foreignId('ditutup_oleh')->nullable()->constrained('users')->restrictOnDelete();
            $table->dateTime('ditutup_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropForeign(['ditutup_oleh']);
            $table->dropColumn(['catatan_penutupan', 'ditutup_oleh', 'ditutup_pada']);
        });
    }
};
