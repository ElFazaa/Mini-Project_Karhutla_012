<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterLaporanRequest;
use App\Models\Laporan;
use App\Models\Peninjauan;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PeninjauanController extends Controller
{
    public function index(FilterLaporanRequest $request)
    {
        $laporan = Laporan::with('wilayah')
            ->filter($request->validated())
            ->latest('id')->paginate(10)->withQueryString();
        $wilayah = Wilayah::orderBy('nama')->get();

        return view('pemantau.index', compact('laporan', 'wilayah'));
    }

    public function show(int $id)
    {
        $laporan = Laporan::with([
            'wilayah', 'peninjauan.pemantau', 'penerusanInformasi.koordinator', 'penutup',
        ])->findOrFail($id);
        $laporanTerkait = Laporan::where('wilayah_id', $laporan->wilayah_id)
            ->where('id', '!=', $laporan->id)
            ->latest()->limit(5)->get();

        return view('pemantau.show', compact('laporan', 'laporanTerkait'));
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

            abort_unless($laporan->status === 'baru', 403,
                'Hanya laporan Baru yang dapat ditinjau. Laporan yang perlu perbaikan harus dikirim ulang oleh pelapor.');

            $data = $request->validate([
                'hasil' => ['required', 'in:perlu_perbaikan,perlu_diteruskan,selesai'],
                'catatan' => ['required', 'string', 'max:5000'],
            ]);

            $peninjauan = new Peninjauan($data);
            $peninjauan->laporan_id = $laporan->id;
            $peninjauan->pemantau_id = $request->user()->id;
            $peninjauan->save();

            $laporan->status = $data['hasil'];
            $laporan->save();
        });

        return redirect()->route('pemantau.show', $id)
            ->with('success', 'Hasil peninjauan berhasil disimpan.');
    }
}
