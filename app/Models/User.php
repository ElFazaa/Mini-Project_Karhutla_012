<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'pelapor_id');
    }

    public function peninjauan()
    {
        return $this->hasMany(Peninjauan::class, 'pemantau_id');
    }

    public function penerusanInformasi()
    {
        return $this->hasMany(PenerusanInformasi::class, 'koordinator_id');
    }
}