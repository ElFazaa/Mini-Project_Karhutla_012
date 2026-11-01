@extends('layouts.app')

@section('content')
    <h1>{{ $akun->exists ? 'Edit Akun' : 'Tambah Akun' }}</h1>

    <form method="POST" action="{{ $akun->exists ? route('akun.update', $akun->id) : route('akun.store') }}">
        @csrf
        @if ($akun->exists)
            @method('PUT')
        @endif

        <label for="name">Nama</label>
        <input id="name" name="name" maxlength="100" value="{{ old('name', $akun->name) }}" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" maxlength="255"
               value="{{ old('email', $akun->email) }}" required>

        <label for="role">Role</label>
        <select id="role" name="role" required>
            @foreach (['pelapor', 'pemantau', 'koordinator'] as $role)
                <option value="{{ $role }}" @selected(old('role', $akun->role ?? 'pelapor') === $role)>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
        @if ($akun->exists && $akun->id === auth()->id())
            <p>Role akun yang sedang digunakan harus tetap Koordinator.</p>
        @endif

        <label for="password">{{ $akun->exists ? 'Password baru (opsional)' : 'Password' }}</label>
        <input type="password" id="password" name="password" minlength="8" maxlength="72"
               autocomplete="new-password" @required(! $akun->exists)>
        <small>Minimal 8 karakter. Saat edit, kosongkan untuk mempertahankan password lama.</small>

        <label for="password_confirmation">Konfirmasi password</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               minlength="8" maxlength="72" autocomplete="new-password" @required(! $akun->exists)>

        <button type="submit">Simpan akun</button>
    </form>

    <p><a href="{{ route('akun.index') }}">Kembali ke daftar akun</a></p>
@endsection
