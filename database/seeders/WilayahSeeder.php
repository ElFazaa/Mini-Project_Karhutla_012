<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Desa Contoh A', 'Desa Contoh B', 'Desa Contoh C'] as $nama) {
            Wilayah::firstOrCreate(
                ['nama' => $nama],
                ['keterangan' => 'Wilayah dummy untuk pengujian']
            );
        }
    }
}