@extends('layouts.app')

@section('content')
    <h1>Edit Laporan</h1>

    @if ($laporan->status === 'perlu_perbaikan')
        <p>Setelah disimpan, laporan dikirim kembali untuk ditinjau.</p>
    @endif

    <form method="POST" action="{{ route('laporan.update', $laporan->id) }}">
        @csrf
        @method('PUT')

        <label for="judul">Judul laporan</label>
        <input id="judul" name="judul" maxlength="150"
               value="{{ old('judul', $laporan->judul) }}" required>

        <label for="wilayah_id">Wilayah</label>
        <select id="wilayah_id" name="wilayah_id" required>
            @foreach ($wilayah as $item)
                <option value="{{ $item->id }}"
                    @selected(old('wilayah_id', $laporan->wilayah_id) == $item->id)>
                    {{ $item->nama }}
                </option>
            @endforeach
        </select>

        <label for="deskripsi">Kondisi yang dilihat</label>
        <textarea id="deskripsi" name="deskripsi" rows="4"
                  required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>

        <label for="detail_lokasi">Detail atau patokan lokasi</label>
        <textarea id="detail_lokasi" name="detail_lokasi" rows="3"
                  required>{{ old('detail_lokasi', $laporan->detail_lokasi) }}</textarea>

        <label for="latitude">Latitude</label>
        <input type="number" id="latitude" name="latitude" step="any"
               min="-90" max="90" value="{{ old('latitude', $laporan->latitude) }}" required>

        <label for="longitude">Longitude</label>
        <input type="number" id="longitude" name="longitude" step="any"
               min="-180" max="180" value="{{ old('longitude', $laporan->longitude) }}" required>

        <label for="waktu_kejadian">Waktu kejadian</label>
        <input type="datetime-local" id="waktu_kejadian" name="waktu_kejadian"
               value="{{ old('waktu_kejadian', $laporan->waktu_kejadian->format('Y-m-d\TH:i')) }}"
               required>

        <button type="submit">
            {{ $laporan->status === 'perlu_perbaikan' ? 'Simpan dan kirim ulang' : 'Simpan perubahan' }}
        </button>
    </form>

    <p><a href="{{ route('laporan.show', $laporan->id) }}">Kembali ke detail</a></p>
@endsection
