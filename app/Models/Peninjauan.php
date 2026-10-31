<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peninjauan extends Model
{
    protected $table = 'peninjauan';

    protected $fillable = [
        'hasil',
        'catatan',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public function pemantau()
    {
        return $this->belongsTo(User::class, 'pemantau_id');
    }
}