<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karhutla</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }

        main {
            max-width: 900px;
            margin: auto;
            padding: 24px;
            background: white;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 16px;
        }

        input, select, textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            margin-top: 6px;
        }

        button {
            padding: 10px 16px;
            margin-top: 16px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .error { color: #b91c1c; }
        .success { color: #166534; }
    </style>
</head>
<body>
    <main>
        <a href="{{ route('dashboard') }}">Beranda</a>
        @if (in_array(auth()->user()->role, ['pemantau', 'koordinator'], true))
            | <a href="{{ route('rekap.index') }}">Rekap laporan</a>
        @endif

        @if (session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <ul class="error">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @yield('content')
    </main>
</body>
</html>
