@extends('layouts.app')

@section('content')
    <h1>Rekap Laporan Karhutla</h1>
    <p>Ringkasan dihitung dari data laporan yang tersimpan. Filter tanggal menggunakan tanggal pembuatan laporan.</p>

    <form method="GET" action="{{ route('rekap.index') }}">
        <label for="wilayah_id">Wilayah</label>
        <select id="wilayah_id" name="wilayah_id">
            <option value="">Semua wilayah</option>
            @foreach ($wilayah as $item)
                <option value="{{ $item->id }}" @selected(request('wilayah_id') == $item->id)>
                    {{ $item->nama }}
                </option>
            @endforeach
        </select>

        <label for="tanggal_awal">Tanggal awal</label>
        <input type="date" id="tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}">

        <label for="tanggal_akhir">Tanggal akhir</label>
        <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">

        <button type="submit">Terapkan</button>
        <a href="{{ route('rekap.index') }}">Reset filter</a>
    </form>

    <h2>Ringkasan</h2>
    <dl>
        <dt>Total laporan</dt>
        <dd>{{ $total }}</dd>
        <dt>Laporan aktif</dt>
        <dd>{{ $aktif }}</dd>
        <dt>Laporan selesai</dt>
        <dd>{{ $selesai }}</dd>
    </dl>
    <p>Laporan aktif mencakup status Baru, Perlu perbaikan, Perlu diteruskan, dan Diteruskan.</p>

    <h2>Jumlah Laporan per Wilayah</h2>
    <table>
        <thead>
            <tr>
                <th>Wilayah</th>
                <th>Total</th>
                <th>Aktif</th>
                <th>Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->total_laporan }}</td>
                    <td>{{ $item->laporan_aktif }}</td>
                    <td>{{ $item->laporan_selesai }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada wilayah yang tersedia.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <td>{{ $total }}</td>
                <td>{{ $aktif }}</td>
                <td>{{ $selesai }}</td>
            </tr>
        </tfoot>
    </table>
@endsection
