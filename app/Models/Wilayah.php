<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'wilayah_id');
    }
}