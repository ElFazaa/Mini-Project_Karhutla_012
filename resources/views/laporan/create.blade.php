@extends('layouts.app')

@section('content')
    <h1>Buat Laporan Karhutla</h1>

    <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data">
        @csrf

        <label for="judul">Judul laporan</label>
        <input
            id="judul"
            name="judul"
            value="{{ old('judul') }}"
            maxlength="150"
            required
        >

        <label for="wilayah_id">Wilayah</label>
        <select id="wilayah_id" name="wilayah_id"
                data-ringkasan-url="{{ url('/wilayah') }}" required>
            <option value="">Pilih wilayah</option>

            @foreach ($wilayah as $item)
                <option
                    value="{{ $item->id }}"
                    @selected(old('wilayah_id') == $item->id)
                >
                    {{ $item->nama }}
                </option>
            @endforeach
        </select>
        <p id="ringkasan-wilayah" role="status" aria-live="polite">
            Pilih wilayah untuk melihat jumlah laporan aktif.
        </p>
        <small>Laporan aktif adalah laporan yang prosesnya belum selesai.</small>

        <label for="deskripsi">Kondisi yang dilihat</label>
        <textarea id="deskripsi" name="deskripsi" rows="4"
            required>{{ old('deskripsi') }}</textarea>

        <label for="detail_lokasi">Detail atau patokan lokasi</label>
        <textarea id="detail_lokasi" name="detail_lokasi" rows="3"
            required>{{ old('detail_lokasi') }}</textarea>

        <label for="latitude">Latitude</label>
        <input
            type="number"
            id="latitude"
            name="latitude"
            step="any"
            min="-90"
            max="90"
            value="{{ old('latitude') }}"
            required
        >

        <label for="longitude">Longitude</label>
        <input
            type="number"
            id="longitude"
            name="longitude"
            step="any"
            min="-180"
            max="180"
            value="{{ old('longitude') }}"
            required
        >

        <label for="waktu_kejadian">Waktu kejadian</label>
        <input
            type="datetime-local"
            id="waktu_kejadian"
            name="waktu_kejadian"
            value="{{ old('waktu_kejadian') }}"
            required
        >

        <label for="bukti">Bukti pendukung (opsional)</label>
        <input
            type="file"
            id="bukti"
            name="bukti"
            accept=".jpg,.jpeg,.png,.pdf"
        >
        <small>JPG/JPEG, PNG, atau PDF. Maksimal 2 MB.</small>

        <button type="submit">Kirim laporan</button>
    </form>

    <p>
        <a href="{{ route('laporan.index') }}">Kembali ke daftar</a>
    </p>

    <script src="{{ asset('js/ringkasan-wilayah.js') }}" defer></script>
@endsection
