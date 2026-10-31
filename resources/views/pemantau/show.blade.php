@extends('layouts.app')

@section('content')
    <h1>Peninjauan Laporan</h1>

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
        <p><a href="{{ route('pemantau.bukti', $laporan->id) }}"
              target="_blank" rel="noopener noreferrer">Buka bukti</a></p>
    @else
        <p>Tidak ada bukti yang dilampirkan.</p>
    @endif

    <h2>Laporan Lain di Wilayah yang Sama</h2>
    <p>Maksimal lima laporan terbaru sebagai bahan perbandingan.</p>
    <ul>
        @forelse ($laporanTerkait as $item)
            <li>
                <a href="{{ route('pemantau.show', $item->id) }}">{{ $item->judul }}</a>
                — {{ $item->waktu_kejadian->format('d-m-Y H:i') }}
                — {{ str_replace('_', ' ', $item->status) }}
            </li>
        @empty
            <li>Belum ada laporan lain di wilayah ini.</li>
        @endforelse
    </ul>

    @include('laporan.riwayat-peninjauan')
    @include('laporan.riwayat-penerusan')

    @if ($laporan->status === 'baru')
        <h2>Hasil Peninjauan</h2>
        <p>Peninjauan memeriksa informasi, bukan menetapkan kejadian kebakaran secara resmi.</p>
        <form method="POST" action="{{ route('pemantau.peninjauan.store', $laporan->id) }}">
            @csrf
            <label for="hasil">Keputusan</label>
            <select id="hasil" name="hasil" required>
                <option value="">Pilih hasil</option>
                <option value="perlu_perbaikan" @selected(old('hasil') === 'perlu_perbaikan')>Perlu perbaikan</option>
                <option value="perlu_diteruskan" @selected(old('hasil') === 'perlu_diteruskan')>Perlu diteruskan</option>
                <option value="selesai" @selected(old('hasil') === 'selesai')>Selesai tanpa penerusan</option>
            </select>

            <label for="catatan">Catatan atau alasan</label>
            <textarea id="catatan" name="catatan" rows="4" maxlength="5000"
                      required>{{ old('catatan') }}</textarea>
            <p>Selesai berarti proses pada aplikasi ditutup, bukan pernyataan bahwa kebakaran telah padam.</p>
            <button type="submit">Simpan peninjauan</button>
        </form>
    @else
        <p>Laporan pada status ini tidak dapat ditinjau ulang. Laporan Perlu perbaikan harus dikirim ulang oleh pelapor terlebih dahulu.</p>
    @endif

    <p><a href="{{ route('pemantau.index') }}">Kembali ke daftar laporan</a></p>
@endsection
