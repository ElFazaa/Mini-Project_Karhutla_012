@extends('layouts.app')

@section('content')
    <h1>Detail Laporan</h1>

    <p><strong>Judul:</strong> {{ $laporan->judul }}</p>
    <p><strong>Wilayah:</strong> {{ $laporan->wilayah->nama }}</p>
    <p><strong>Status:</strong> {{ str_replace('_', ' ', $laporan->status) }}</p>
    @if (in_array($laporan->status, ['baru', 'perlu_perbaikan'], true))
        <p><a href="{{ route('laporan.edit', $laporan->id) }}">Edit laporan</a></p>
    @endif
    <p>
        <strong>Waktu kejadian:</strong>
        {{ $laporan->waktu_kejadian->format('d-m-Y H:i') }}
    </p>

    <h2>Kondisi yang dilaporkan</h2>
    <p style="white-space: pre-wrap">{{ $laporan->deskripsi }}</p>

    <h2>Lokasi</h2>
    <p style="white-space: pre-wrap">{{ $laporan->detail_lokasi }}</p>
    <p><strong>Latitude:</strong> {{ $laporan->latitude }}</p>
    <p><strong>Longitude:</strong> {{ $laporan->longitude }}</p>

    <h2>Bukti pendukung</h2>
    @if ($laporan->bukti_path)
        <p>
            <a href="{{ route('laporan.bukti', $laporan->id) }}"
               target="_blank" rel="noopener noreferrer">Buka bukti</a>
        </p>
    @else
        <p>Tidak ada bukti yang dilampirkan.</p>
    @endif

    @include('laporan.riwayat-peninjauan')

    <p><a href="{{ route('laporan.index') }}">Kembali ke daftar laporan</a></p>
@endsection
