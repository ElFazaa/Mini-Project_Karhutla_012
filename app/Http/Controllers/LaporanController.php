<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Wilayah;
use Illuminate\Http\Request;

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
        ]);

        $laporan = new Laporan($data);
        $laporan->pelapor_id = $request->user()->id;
        $laporan->status = 'baru';
        $laporan->save();

        return redirect()
            ->route('laporan.index')
            ->with('success', 'Laporan berhasil dibuat.');
    }
}