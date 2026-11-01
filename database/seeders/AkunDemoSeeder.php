<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkunDemoSeeder extends Seeder
{
    public function run(): void
    {
        $akun = [
            [
                'name' => 'Pelapor Demo',
                'email' => 'pelapor@example.test',
                'role' => 'pelapor',
            ],
            [
                'name' => 'Pelapor Kedua Demo',
                'email' => 'pelapor2@example.test',
                'role' => 'pelapor',
            ],
            [
                'name' => 'Pemantau Demo',
                'email' => 'pemantau@example.test',
                'role' => 'pemantau',
            ],
            [
                'name' => 'Koordinator Demo',
                'email' => 'koordinator@example.test',
                'role' => 'koordinator',
            ],
        ];

        foreach ($akun as $data) {
            $user = User::firstOrNew([
                'email' => $data['email'],
            ]);

            $user->name = $data['name'];
            $user->role = $data['role'];
            $user->password = Hash::make('DemoKarhutla012');
            $user->save();
        }
    }
}
