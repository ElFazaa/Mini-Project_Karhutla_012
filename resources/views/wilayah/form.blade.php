@extends('layouts.app')

@section('content')
    <h1>{{ $wilayah->exists ? 'Edit Wilayah' : 'Tambah Wilayah' }}</h1>

    <form method="POST" action="{{ $wilayah->exists ? route('wilayah.update', $wilayah->id) : route('wilayah.store') }}">
        @csrf
        @if ($wilayah->exists)
            @method('PUT')
        @endif

        <label for="nama">Nama wilayah</label>
        <input id="nama" name="nama" maxlength="150"
               value="{{ old('nama', $wilayah->nama) }}" required>
        <small>Gunakan nama yang jelas, misalnya nama desa beserta kecamatannya.</small>

        <label for="keterangan">Keterangan (opsional)</label>
        <textarea id="keterangan" name="keterangan" rows="4"
                  maxlength="2000">{{ old('keterangan', $wilayah->keterangan) }}</textarea>

        <button type="submit">Simpan wilayah</button>
    </form>

    <p><a href="{{ route('wilayah.index') }}">Kembali ke daftar wilayah</a></p>
@endsection
