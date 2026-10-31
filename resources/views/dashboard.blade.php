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

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </main>
</body>
</html>