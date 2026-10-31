<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerusanInformasi extends Model
{
    protected $table = 'penerusan_informasi';

    protected $fillable = [
        'pihak_tujuan',
        'waktu_penerusan',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'waktu_penerusan' => 'datetime',
        ];
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public function koordinator()
    {
        return $this->belongsTo(User::class, 'koordinator_id');
    }
}