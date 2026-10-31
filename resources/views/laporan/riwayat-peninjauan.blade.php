<h2>Catatan Peninjauan</h2>

@forelse ($laporan->peninjauan->sortByDesc('id') as $item)
    <div>
        <p>
            <strong>{{ str_replace('_', ' ', $item->hasil) }}</strong>
            — {{ $item->pemantau->name }}
            ({{ $item->created_at->format('d-m-Y H:i') }})
        </p>
        <p style="white-space: pre-wrap">{{ $item->catatan }}</p>
        <hr>
    </div>
@empty
    <p>Belum ada catatan peninjauan.</p>
@endforelse
