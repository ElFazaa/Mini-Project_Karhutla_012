<form method="GET" action="{{ $action }}">
    <label for="q">Cari judul laporan</label>
    <input id="q" name="q" value="{{ request('q') }}" maxlength="150">

    <label for="filter_wilayah">Wilayah</label>
    <select id="filter_wilayah" name="wilayah_id">
        <option value="">Semua wilayah</option>
        @foreach ($wilayah as $item)
            <option value="{{ $item->id }}" @selected(request('wilayah_id') == $item->id)>
                {{ $item->nama }}
            </option>
        @endforeach
    </select>

    <label for="filter_status">Status</label>
    <select id="filter_status" name="status">
        <option value="">Semua status</option>
        @foreach (['baru', 'perlu_perbaikan', 'perlu_diteruskan', 'diteruskan', 'selesai'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>
                {{ ucfirst(str_replace('_', ' ', $status)) }}
            </option>
        @endforeach
    </select>

    <button type="submit">Terapkan</button>
    <a href="{{ $action }}">Reset filter</a>
</form>

<p>Ditemukan {{ $laporan->total() }} laporan.</p>
