<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laporan = $request->user()
            ->laporan()
            ->with('wilayah')
            ->latest()
            ->paginate(10);

        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        $wilayah = Wilayah::orderBy('nama')->get();

        return view('laporan.create', compact('wilayah'));
    }

    public function show(Request $request, int $id)
    {
        $laporan = $request->user()->laporan()
            ->with('wilayah')
            ->findOrFail($id);

        return view('laporan.show', compact('laporan'));
    }

    public function bukti(Request $request, int $id)
    {
        $laporan = $request->user()->laporan()->findOrFail($id);

        abort_unless(
            $laporan->bukti_path && Storage::disk('local')->exists($laporan->bukti_path),
            404,
            'Bukti tidak tersedia.'
        );

        return Storage::disk('local')->response($laporan->bukti_path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'wilayah_id' => ['required', 'integer', 'exists:wilayah,id'],
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'detail_lokasi' => ['required', 'string', 'max:1000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'waktu_kejadian' => ['required', 'date'],
            'bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        unset($data['bukti']);

        $pathBukti = null;

        if ($request->hasFile('bukti')) {
            $pathBukti = $request->file('bukti')->store('bukti', 'local');

            if ($pathBukti === false) {
                throw new \RuntimeException('Bukti gagal disimpan.');
            }
        }

        try {
            $laporan = new Laporan($data);
            $laporan->pelapor_id = $request->user()->id;
            $laporan->status = 'baru';
            $laporan->bukti_path = $pathBukti;
            $laporan->save();
        } catch (\Throwable $error) {
            if ($pathBukti !== null) {
                Storage::disk('local')->delete($pathBukti);
            }

            throw $error;
        }

        return redirect()
            ->route('laporan.index')
            ->with('success', 'Laporan berhasil dibuat.');
    }
}
