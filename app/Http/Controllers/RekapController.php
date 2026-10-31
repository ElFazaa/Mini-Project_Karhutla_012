<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $aturanTanggalAkhir = ['nullable', 'date_format:Y-m-d'];

        if ($request->filled('tanggal_awal')) {
            $aturanTanggalAkhir[] = 'after_or_equal:tanggal_awal';
        }

        $filter = $request->validate([
            'wilayah_id' => ['nullable', 'integer', 'exists:wilayah,id'],
            'tanggal_awal' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_akhir' => $aturanTanggalAkhir,
        ]);

        $batasTanggal = function ($query) use ($filter) {
            if (! empty($filter['tanggal_awal'])) {
                $query->whereDate('created_at', '>=', $filter['tanggal_awal']);
            }

            if (! empty($filter['tanggal_akhir'])) {
                $query->whereDate('created_at', '<=', $filter['tanggal_akhir']);
            }
        };

        $query = Wilayah::query();

        if (! empty($filter['wilayah_id'])) {
            $query->where('id', $filter['wilayah_id']);
        }

        $rekap = $query->withCount([
            'laporan as total_laporan' => $batasTanggal,
            'laporan as laporan_aktif' => function ($query) use ($batasTanggal) {
                $batasTanggal($query);
                $query->where('status', '!=', 'selesai');
            },
            'laporan as laporan_selesai' => function ($query) use ($batasTanggal) {
                $batasTanggal($query);
                $query->where('status', 'selesai');
            },
        ])->orderBy('nama')->get();

        $total = $rekap->sum('total_laporan');
        $aktif = $rekap->sum('laporan_aktif');
        $selesai = $rekap->sum('laporan_selesai');
        $wilayah = Wilayah::orderBy('nama')->get();

        return view('rekap.index', compact('rekap', 'total', 'aktif', 'selesai', 'wilayah'));
    }
}
