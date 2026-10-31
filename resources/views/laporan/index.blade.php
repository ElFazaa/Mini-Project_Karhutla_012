@extends('layouts.app')

@section('content')
    <h1>Laporan Saya</h1>

    <a href="{{ route('laporan.create') }}">Buat laporan</a>

    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Wilayah</th>
                <th>Waktu kejadian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $item)
                <tr>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->wilayah->nama }}</td>
                    <td>
                        {{ $item->waktu_kejadian->format('d-m-Y H:i') }}
                    </td>
                    <td>{{ str_replace('_', ' ', $item->status) }}</td>
                    <td>
                        <a href="{{ route('laporan.show', $item->id) }}">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p>
        @if ($laporan->previousPageUrl())
            <a href="{{ $laporan->previousPageUrl() }}">Sebelumnya</a>
        @endif

        Halaman {{ $laporan->currentPage() }}
        dari {{ $laporan->lastPage() }}

        @if ($laporan->nextPageUrl())
            <a href="{{ $laporan->nextPageUrl() }}">Berikutnya</a>
        @endif
    </p>
@endsection
