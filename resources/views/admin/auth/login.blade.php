<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | Delight Bakery</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: 'DM Sans', sans-serif;
            color: #301c0d;
            background:
                radial-gradient(circle at 10% 15%, #e5d5c6 0, transparent 30%),
                radial-gradient(circle at 90% 85%, #ddcebf 0, transparent 30%),
                #eadfd3;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 42px;
            background: #fffefa;
            border: 1px solid #d9c8b7;
            border-radius: 26px;
            box-shadow: 0 24px 60px rgba(48, 28, 13, .14);
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 68px;
            height: 68px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: #4a2616;
            color: #eadfd3;
            font-size: 32px;
        }

        .brand h1 {
            margin: 0;
            color: #6b3a24;
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 34px;
            font-weight: 400;
        }

        .brand p {
            margin: 7px 0 0;
            color: #85705f;
            font-size: 14px;
        }

        .welcome {
            margin-bottom: 25px;
        }

        .welcome h2 {
            margin: 0 0 7px;
            font-size: 21px;
        }

        .welcome p {
            margin: 0;
            color: #85705f;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 19px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            height: 49px;
            padding: 0 14px;
            border: 1px solid #d9c8b7;
            border-radius: 11px;
            outline: none;
            background: #fffefa;
            color: #301c0d;
            font-family: inherit;
            font-size: 14px;
            transition: border .2s, box-shadow .2s;
        }

        input:focus {
            border-color: #6b3a24;
            box-shadow: 0 0 0 3px rgba(107, 58, 36, .12);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 65px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #6b3a24;
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 5px 0 23px;
            color: #665344;
            font-size: 14px;
        }

        .remember input {
            accent-color: #6b3a24;
            width: 16px;
            height: 16px;
        }

        .login-button {
            width: 100%;
            height: 51px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #6b3a24, #4a2616);
            color: white;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 9px 20px rgba(74, 38, 22, .22);
        }

        .error-message {
            margin-top: 7px;
            color: #b42318;
            font-size: 13px;
        }

        .alert {
            padding: 12px 14px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 13px;
        }

        .alert-error {
            background: #fff0ee;
            color: #a12a20;
            border: 1px solid #f2c5bf;
        }

        .alert-success {
            background: #edf8ef;
            color: #28613a;
            border: 1px solid #c5e5cd;
        }

        .footer {
            margin-top: 27px;
            text-align: center;
            color: #85705f;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 23px;
            }

            .brand h1 {
                font-size: 29px;
            }
        }
    </style>
</head>

<body>

    <main class="login-card">

        <div class="brand">
            <div class="brand-icon">??</div>
            <h1>Delight Bakery</h1>
            <p>Bakery & Cake Shop Management System</p>
        </div>

        <div class="welcome">
            <h2>Welcome Back!</h2>
            <p>Sign in with your staff account to continue.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your staff email"
                    autocomplete="username"
                    required
                    autofocus
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword()"
                    >
                        Show
                    </button>
                </div>
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <button type="submit" class="login-button">
                Sign In to Dashboard
            </button>
        </form>

        <div class="footer">
            &copy; {{ date('Y') }} Delight Bakery. All rights reserved.
        </div>

    </main>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const button = document.querySelector('.toggle-password');

            if (password.type === 'password') {
                password.type = 'text';
                button.textContent = 'Hide';
            } else {
                password.type = 'password';
                button.textContent = 'Show';
            }
        }
    </script>

</body>
</html>
