<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Karhutla</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 24px;
        }

        main {
            max-width: 360px;
            margin: 60px auto;
            padding: 24px;
            background: white;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 16px;
        }

        input, button {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            margin-top: 8px;
        }

        button {
            margin-top: 20px;
            background: #166534;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error {
            color: #b91c1c;
        }
    </style>
</head>
<body>
    <main>
        <h1>Login Karhutla</h1>
        <p>Masuk untuk mengakses aplikasi.</p>

        @if ($errors->any())
            <ul class="error">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="username"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>