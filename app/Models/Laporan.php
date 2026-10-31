<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'wilayah_id',
        'judul',
        'deskripsi',
        'detail_lokasi',
        'latitude',
        'longitude',
        'waktu_kejadian',
        'bukti_path',
    ];

    protected function casts(): array
    {
        return [
            'waktu_kejadian' => 'datetime',
            'ditutup_pada' => 'datetime',
        ];
    }

    public function scopeFilter(Builder $query, array $filter): Builder
    {
        if (isset($filter['q']) && $filter['q'] !== '') {
            $query->where('judul', 'like', '%'.$filter['q'].'%');
        }

        if (! empty($filter['wilayah_id'])) {
            $query->where('wilayah_id', $filter['wilayah_id']);
        }

        if (! empty($filter['status'])) {
            $query->where('status', $filter['status']);
        }

        return $query;
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function peninjauan()
    {
        return $this->hasMany(Peninjauan::class, 'laporan_id');
    }

    public function penerusanInformasi()
    {
        return $this->hasMany(PenerusanInformasi::class, 'laporan_id');
    }

    public function penutup()
    {
        return $this->belongsTo(User::class, 'ditutup_oleh');
    }
}
