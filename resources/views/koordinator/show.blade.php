@extends('layouts.app')

@section('content')
    <h1>Koordinasi Laporan</h1>
    <p><strong>Judul:</strong> {{ $laporan->judul }}</p>
    <p><strong>Wilayah:</strong> {{ $laporan->wilayah->nama }}</p>
    <p><strong>Status:</strong> {{ str_replace('_', ' ', $laporan->status) }}</p>
    <p><strong>Waktu kejadian:</strong> {{ $laporan->waktu_kejadian->format('d-m-Y H:i') }}</p>
    <h2>Kondisi yang dilaporkan</h2>
    <p style="white-space: pre-wrap">{{ $laporan->deskripsi }}</p>
    <h2>Lokasi</h2>
    <p style="white-space: pre-wrap">{{ $laporan->detail_lokasi }}</p>
    <p>Latitude: {{ $laporan->latitude }}; Longitude: {{ $laporan->longitude }}</p>

    @if ($laporan->bukti_path)
        <p><a href="{{ route('koordinator.bukti', $laporan->id) }}"
              target="_blank" rel="noopener noreferrer">Buka bukti</a></p>
    @else
        <p>Tidak ada bukti yang dilampirkan.</p>
    @endif

    @include('laporan.riwayat-peninjauan')
    @include('laporan.riwayat-penerusan')

    @if (in_array($laporan->status, ['perlu_diteruskan', 'diteruskan'], true))
        <h2>Catat Penerusan Informasi</h2>
        <p>Form ini mencatat penerusan yang dilakukan di luar aplikasi, bukan mengirim pesan otomatis.</p>
        <form method="POST" action="{{ route('koordinator.penerusan.store', $laporan->id) }}">
            @csrf
            <label for="pihak_tujuan">Pihak tujuan</label>
            <input id="pihak_tujuan" name="pihak_tujuan" maxlength="150"
                   value="{{ old('pihak_tujuan') }}" required>

            <label for="waktu_penerusan">Waktu penerusan</label>
            <input type="datetime-local" id="waktu_penerusan" name="waktu_penerusan"
                   value="{{ old('waktu_penerusan') }}" required>

            <label for="catatan">Catatan penerusan</label>
            <textarea id="catatan" name="catatan" rows="4" maxlength="5000"
                      required>{{ old('catatan') }}</textarea>
            <button type="submit">Simpan penerusan</button>
        </form>
    @endif

    @if ($laporan->status === 'diteruskan')
        <h2>Tutup Proses Laporan</h2>
        <p>Penutupan hanya menyelesaikan proses pada aplikasi komunitas.</p>
        <form method="POST" action="{{ route('koordinator.tutup', $laporan->id) }}">
            @csrf
            <label for="catatan_penutupan">Alasan penutupan</label>
            <textarea id="catatan_penutupan" name="catatan_penutupan" rows="4"
                      maxlength="5000" required>{{ old('catatan_penutupan') }}</textarea>
            <button type="submit">Tutup laporan</button>
        </form>
    @elseif (! in_array($laporan->status, ['perlu_diteruskan'], true))
        <p>Tidak ada tindakan penerusan atau penutupan yang dapat dilakukan pada status ini.</p>
    @endif

    <p><a href="{{ route('koordinator.index') }}">Kembali ke daftar laporan</a></p>
@endsection
