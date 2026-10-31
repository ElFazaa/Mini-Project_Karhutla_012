<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WilayahController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $query = Wilayah::withCount('laporan');

        if (isset($filter['q']) && $filter['q'] !== '') {
            $query->where('nama', 'like', '%'.$filter['q'].'%');
        }

        $wilayah = $query->orderBy('nama')->orderBy('id')->paginate(10)->withQueryString();

        return view('wilayah.index', compact('wilayah'));
    }

    public function create()
    {
        return view('wilayah.form', ['wilayah' => new Wilayah()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        Wilayah::create($data);

        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        return view('wilayah.form', ['wilayah' => Wilayah::findOrFail($id)]);
    }

    public function update(Request $request, int $id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        $wilayah->update($data);

        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        DB::transaction(function () use ($id) {
            $wilayah = Wilayah::lockForUpdate()->findOrFail($id);

            if ($wilayah->laporan()->exists()) {
                throw ValidationException::withMessages([
                    'wilayah' => 'Wilayah tidak dapat dihapus karena sudah digunakan dalam laporan.',
                ]);
            }

            $wilayah->delete();
        });

        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil dihapus.');
    }
}
