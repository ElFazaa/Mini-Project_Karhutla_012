@extends('layouts.app')

@section('content')
    <h1>Kelola Akun</h1>
    <a href="{{ route('akun.create') }}">Tambah akun</a>

    <form method="GET" action="{{ route('akun.index') }}">
        <label for="q">Cari nama atau email</label>
        <input id="q" name="q" maxlength="150" value="{{ request('q') }}">

        <label for="filter_role">Role</label>
        <select id="filter_role" name="role">
            <option value="">Semua role</option>
            @foreach (['pelapor', 'pemantau', 'koordinator'] as $role)
                <option value="{{ $role }}" @selected(request('role') === $role)>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>

        <button type="submit">Terapkan</button>
        <a href="{{ route('akun.index') }}">Reset filter</a>
    </form>

    <p>Ditemukan {{ $akun->total() }} akun.</p>
    <table>
        <thead>
            <tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($akun as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ ucfirst($item->role) }}</td>
                    <td><a href="{{ route('akun.edit', $item->id) }}">Edit</a></td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada akun yang sesuai.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p>
        @if ($akun->previousPageUrl())
            <a href="{{ $akun->previousPageUrl() }}">Sebelumnya</a>
        @endif
        Halaman {{ $akun->currentPage() }} dari {{ $akun->lastPage() }}
        @if ($akun->nextPageUrl())
            <a href="{{ $akun->nextPageUrl() }}">Berikutnya</a>
        @endif
    </p>
@endsection
