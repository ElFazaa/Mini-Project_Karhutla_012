<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\PenerusanInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenerusanInformasiController extends Controller
{
    public function index()
    {
        $laporan = Laporan::with('wilayah')->latest()->paginate(10);

        return view('koordinator.index', compact('laporan'));
    }

    public function show(int $id)
    {
        $laporan = Laporan::with([
            'wilayah', 'peninjauan.pemantau', 'penerusanInformasi.koordinator', 'penutup',
        ])->findOrFail($id);

        return view('koordinator.show', compact('laporan'));
    }

    public function bukti(int $id)
    {
        $laporan = Laporan::findOrFail($id);

        abort_unless(
            $laporan->bukti_path && Storage::disk('local')->exists($laporan->bukti_path),
            404, 'Bukti tidak tersedia.'
        );

        return Storage::disk('local')->response($laporan->bukti_path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function store(Request $request, int $id)
    {
        DB::transaction(function () use ($request, $id) {
            $laporan = Laporan::lockForUpdate()->findOrFail($id);

            abort_unless(in_array($laporan->status, ['perlu_diteruskan', 'diteruskan'], true),
                403, 'Laporan belum dapat diteruskan atau prosesnya sudah selesai.');

            $data = $request->validate([
                'pihak_tujuan' => ['required', 'string', 'max:150'],
                'waktu_penerusan' => ['required', 'date'],
                'catatan' => ['required', 'string', 'max:5000'],
            ]);

            $penerusan = new PenerusanInformasi($data);
            $penerusan->laporan_id = $laporan->id;
            $penerusan->koordinator_id = $request->user()->id;
            $penerusan->save();

            $laporan->status = 'diteruskan';
            $laporan->save();
        });

        return redirect()->route('koordinator.show', $id)
            ->with('success', 'Penerusan informasi berhasil dicatat.');
    }

    public function tutup(Request $request, int $id)
    {
        DB::transaction(function () use ($request, $id) {
            $laporan = Laporan::lockForUpdate()->findOrFail($id);

            abort_unless($laporan->status === 'diteruskan' && $laporan->penerusanInformasi()->exists(),
                403, 'Hanya laporan yang telah dicatat penerusannya yang dapat ditutup.');

            $data = $request->validate([
                'catatan_penutupan' => ['required', 'string', 'max:5000'],
            ]);

            $laporan->catatan_penutupan = $data['catatan_penutupan'];
            $laporan->ditutup_oleh = $request->user()->id;
            $laporan->ditutup_pada = now();
            $laporan->status = 'selesai';
            $laporan->save();
        });

        return redirect()->route('koordinator.show', $id)
            ->with('success', 'Proses laporan dalam aplikasi telah ditutup.');
    }
}
