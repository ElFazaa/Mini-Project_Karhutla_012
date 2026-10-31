@extends('layouts.app')

@section('content')
    <h1>Kelola Wilayah</h1>
    <a href="{{ route('wilayah.create') }}">Tambah wilayah</a>

    <form method="GET" action="{{ route('wilayah.index') }}">
        <label for="q">Cari nama wilayah</label>
        <input id="q" name="q" maxlength="150" value="{{ request('q') }}">
        <button type="submit">Cari</button>
        <a href="{{ route('wilayah.index') }}">Reset pencarian</a>
    </form>

    <p>Ditemukan {{ $wilayah->total() }} wilayah. Wilayah yang sudah digunakan laporan tidak dapat dihapus.</p>

    <table>
        <thead>
            <tr><th>Nama wilayah</th><th>Keterangan</th><th>Jumlah laporan</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($wilayah as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>{{ $item->laporan_count }}</td>
                    <td>
                        <a href="{{ route('wilayah.edit', $item->id) }}">Edit</a>
                        @if ($item->laporan_count === 0)
                            <form method="POST" action="{{ route('wilayah.destroy', $item->id) }}"
                                  onsubmit="return confirm('Hapus wilayah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                        @else
                            <p>Sudah digunakan laporan</p>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada wilayah yang sesuai.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p>
        @if ($wilayah->previousPageUrl())
            <a href="{{ $wilayah->previousPageUrl() }}">Sebelumnya</a>
        @endif
        Halaman {{ $wilayah->currentPage() }} dari {{ $wilayah->lastPage() }}
        @if ($wilayah->nextPageUrl())
            <a href="{{ $wilayah->nextPageUrl() }}">Berikutnya</a>
        @endif
    </p>
@endsection
