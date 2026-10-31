<h2>Penerusan Informasi</h2>

@forelse ($laporan->penerusanInformasi->sortByDesc('id') as $item)
    <div>
        <p><strong>Tujuan:</strong> {{ $item->pihak_tujuan }}</p>
        <p>
            Diteruskan pada {{ $item->waktu_penerusan->format('d-m-Y H:i') }}.
            Dicatat oleh {{ $item->koordinator->name }}.
        </p>
        <p style="white-space: pre-wrap">{{ $item->catatan }}</p>
        <hr>
    </div>
@empty
    <p>Belum ada penerusan informasi yang dicatat.</p>
@endforelse

@if ($laporan->catatan_penutupan)
    <h2>Penutupan Laporan</h2>
    <p>
        Ditutup oleh {{ $laporan->penutup->name }}
        pada {{ $laporan->ditutup_pada->format('d-m-Y H:i') }}.
    </p>
    <p style="white-space: pre-wrap">{{ $laporan->catatan_penutupan }}</p>
@endif

@if ($laporan->status === 'selesai')
    <p>Selesai berarti proses dalam aplikasi ditutup, bukan pernyataan bahwa kebakaran telah padam.</p>
@endif
