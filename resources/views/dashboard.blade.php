<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda Karhutla</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 24px;
        }

        main {
            max-width: 700px;
            margin: 30px auto;
            padding: 24px;
            background: white;
            border-radius: 8px;
        }

        button {
            padding: 10px 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main>
        <h1>Beranda Karhutla</h1>

        <p>Selamat datang, {{ auth()->user()->name }}.</p>
        <p>Role: {{ auth()->user()->role }}</p>
        @if (auth()->user()->role === 'pelapor')
         <p>
        <a href="{{ route('pelapor.index') }}">Halaman Pelapor</a>
        </p>
        @elseif (auth()->user()->role === 'pemantau')
        <p>
        <a href="{{ route('pemantau.index') }}">Halaman Pemantau</a>
        </p>
        @elseif (auth()->user()->role === 'koordinator')
        <p>
        <a href="{{ route('koordinator.index') }}">Halaman Koordinator</a>
        </p>
        @endif

        @if (in_array(auth()->user()->role, ['pemantau', 'koordinator'], true))
            <p><a href="{{ route('rekap.index') }}">Rekap laporan</a></p>
        @endif

        @if (auth()->user()->role === 'koordinator')
            <p><a href="{{ route('wilayah.index') }}">Kelola wilayah</a></p>
            <p><a href="{{ route('akun.index') }}">Kelola akun</a></p>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </main>
</body>
</html>
