<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'role' => ['nullable', 'in:pelapor,pemantau,koordinator'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $query = User::query();

        if (isset($filter['q']) && $filter['q'] !== '') {
            $query->where(function ($query) use ($filter) {
                $query->where('name', 'like', '%'.$filter['q'].'%')
                    ->orWhere('email', 'like', '%'.$filter['q'].'%');
            });
        }

        if (! empty($filter['role'])) {
            $query->where('role', $filter['role']);
        }

        $akun = $query->orderBy('name')->orderBy('id')->paginate(10)->withQueryString();

        return view('akun.index', compact('akun'));
    }

    public function create()
    {
        return view('akun.form', ['akun' => new User()]);
    }

    public function store(Request $request)
    {
        $akun = new User();
        $data = $this->validateData($request, $akun);

        $akun->name = $data['name'];
        $akun->email = $data['email'];
        $akun->role = $data['role'];
        $akun->password = $data['password'];
        $akun->save();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        return view('akun.form', ['akun' => User::findOrFail($id)]);
    }

    public function update(Request $request, int $id)
    {
        $akun = User::findOrFail($id);
        $data = $this->validateData($request, $akun);

        if ($akun->id === $request->user()->id && $data['role'] !== 'koordinator') {
            throw ValidationException::withMessages([
                'role' => 'Role akun yang sedang Anda gunakan harus tetap Koordinator.',
            ]);
        }

        $akun->name = $data['name'];

        if ($akun->email !== $data['email']) {
            $akun->email_verified_at = null;
        }

        $akun->email = $data['email'];
        $akun->role = $data['role'];

        if (! empty($data['password'])) {
            $akun->password = $data['password'];
        }

        $akun->save();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    private function validateData(Request $request, User $akun): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($akun->id)],
            'role' => ['required', 'in:pelapor,pemantau,koordinator'],
            'password' => [$akun->exists ? 'nullable' : 'required', 'string', 'min:8', 'max:72', 'confirmed'],
        ]);
    }
}
