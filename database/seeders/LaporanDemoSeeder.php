<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\PenerusanInformasi;
use App\Models\Peninjauan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaporanDemoSeeder extends Seeder
{
    public function run(): void
    {
        $pelapor = User::where('email', 'pelapor@example.test')->firstOrFail();
        $pelaporKedua = User::where('email', 'pelapor2@example.test')->firstOrFail();
        $pemantau = User::where('email', 'pemantau@example.test')->firstOrFail();
        $koordinator = User::where('email', 'koordinator@example.test')->firstOrFail();
        $wilayah = Wilayah::whereIn('nama', ['Desa Contoh A', 'Desa Contoh B', 'Desa Contoh C'])
            ->orderBy('id')->get();

        if ($wilayah->count() !== 3) {
            throw new \RuntimeException('Jalankan WilayahSeeder sebelum LaporanDemoSeeder.');
        }

        $statusDemo = ['baru', 'perlu_perbaikan', 'perlu_diteruskan', 'diteruskan', 'selesai'];
        $contohBukti = file_get_contents(database_path('seeders/fixtures/bukti-demo.png'));

        if ($contohBukti === false) {
            throw new \RuntimeException('File bukti demo tidak tersedia.');
        }

        for ($nomor = 1; $nomor <= 18; $nomor++) {
            $pemilik = $nomor <= 12 ? $pelapor : $pelaporKedua;
            $judul = 'uji coba '.str_pad((string) $nomor, 2, '0', STR_PAD_LEFT);

            if ($pemilik->laporan()->where('judul', $judul)->exists()) {
                continue;
            }

            $status = $statusDemo[($nomor - 1) % count($statusDemo)];
            $waktu = now()->subDays(19 - $nomor)->startOfDay()->addHours(8);
            $pathBukti = $nomor % 3 === 0 ? 'bukti/demo/screenshot'.$nomor.'.png' : null;

            try {
                DB::transaction(function () use ($nomor, $pemilik, $judul, $status, $waktu,
                    $pathBukti, $contohBukti, $wilayah, $pemantau, $koordinator) {
                    if ($pathBukti && ! Storage::disk('local')->put($pathBukti, $contohBukti)) {
                        throw new \RuntimeException('Bukti demo gagal disimpan.');
                    }

                    $laporan = new Laporan([
                        'wilayah_id' => $wilayah[($nomor - 1) % 3]->id,
                        'judul' => $judul,
                        'deskripsi' => 'Data dummy latihan pelaporan indikasi asap. Bukan kejadian nyata.',
                        'detail_lokasi' => 'Lahan contoh dekat jalan desa. Lokasi ini hanya untuk pengujian.',
                        'latitude' => -6.9 + ($nomor / 10000),
                        'longitude' => 107.6 + ($nomor / 10000),
                        'waktu_kejadian' => $waktu,
                    ]);
                    $laporan->pelapor_id = $pemilik->id;
                    $laporan->status = $status;
                    $laporan->bukti_path = $pathBukti;
                    $laporan->created_at = $waktu;
                    $laporan->updated_at = $waktu;

                    $ditutupPemantau = $status === 'selesai' && $nomor % 2 === 0;

                    if ($status === 'selesai' && ! $ditutupPemantau) {
                        $laporan->catatan_penutupan = 'Penerusan informasi dummy sudah dicatat. Proses komunitas ditutup.';
                        $laporan->ditutup_oleh = $koordinator->id;
                        $laporan->ditutup_pada = $waktu->copy()->addHours(3);
                    }

                    $laporan->save();

                    if ($status !== 'baru') {
                        $hasil = $ditutupPemantau ? 'selesai'
                            : ($status === 'perlu_perbaikan' ? 'perlu_perbaikan' : 'perlu_diteruskan');
                        $catatan = match ($hasil) {
                            'perlu_perbaikan' => 'Mohon perjelas patokan lokasi sebelum laporan ditinjau kembali.',
                            'selesai' => 'Laporan dummy ditutup dalam aplikasi tanpa penerusan lebih lanjut.',
                            default => 'Informasi dummy cukup untuk diteruskan kepada pihak terkait.',
                        };

                        $peninjauan = new Peninjauan(['hasil' => $hasil, 'catatan' => $catatan]);
                        $peninjauan->laporan_id = $laporan->id;
                        $peninjauan->pemantau_id = $pemantau->id;
                        $peninjauan->created_at = $waktu->copy()->addHour();
                        $peninjauan->updated_at = $peninjauan->created_at;
                        $peninjauan->save();
                    }

                    if ($status === 'diteruskan' || ($status === 'selesai' && ! $ditutupPemantau)) {
                        $penerusan = new PenerusanInformasi([
                            'pihak_tujuan' => 'Damkar Contoh',
                            'waktu_penerusan' => $waktu->copy()->addHours(2),
                            'catatan' => 'Contoh pencatatan informasi yang diteruskan. Tidak ada pesan nyata yang dikirim.',
                        ]);
                        $penerusan->laporan_id = $laporan->id;
                        $penerusan->koordinator_id = $koordinator->id;
                        $penerusan->created_at = $waktu->copy()->addHours(2);
                        $penerusan->updated_at = $penerusan->created_at;
                        $penerusan->save();
                    }
                });
            } catch (\Throwable $error) {
                if ($pathBukti) {
                    Storage::disk('local')->delete($pathBukti);
                }

                throw $error;
            }
        }
    }
}
