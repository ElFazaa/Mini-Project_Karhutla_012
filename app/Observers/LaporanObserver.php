<?php

namespace App\Observers;

use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanObserver
{
    public function updated(Laporan $laporan): void
    {
        if (! $laporan->wasChanged('status')) {
            return;
        }

        $perubahan = [
            'laporan_id' => $laporan->id,
            'status_sebelum' => $laporan->getOriginal('status'),
            'status_sesudah' => $laporan->status,
            'pengguna_id' => Auth::id(),
            'waktu' => now()->toIso8601String(),
        ];

        // Catat setelah transaksi berhasil, agar rollback tidak menghasilkan log perubahan palsu.
        DB::connection($laporan->getConnectionName())->afterCommit(function () use ($perubahan) {
            Log::channel('single')->info('Perubahan status laporan', $perubahan);
        });
    }
}
