<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Tài Khoản</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/custom-auth.css') }}">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1500&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .register-title {
            color: #fff;
            font-size: 2.5rem;
            letter-spacing: 0.2em;
            margin-bottom: 2rem;
            margin-top: 2rem;
            text-shadow: 0 2px 8px #0008;
        }
        .register-form {
            background: rgba(0,0,0,0.65);
            padding: 2.5rem 2rem 2rem 2rem;
            border-radius: 8px;
            box-shadow: 0 8px 32px #0006;
            min-width: 340px;
            max-width: 400px;
            width: 100%;
        }
        .form-title {
            color: #fff;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: 0.1em;
        }
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-field {
            width: 100%;
            box-sizing: border-box;
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            border: 1.5px solid #7cb342;
            border-radius: 4px;
            background: #fff;
            color: #222;
            font-size: 1rem;
            outline: none;
            transition: border 0.2s;
        }
        .input-field::placeholder {
            color: #bbb;
        }
        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #7cb342;
            font-size: 1.2rem;
        }
        .register-btn {
            width: 100%;
            background: #7cb342;
            color: #fff;
            border: none;
            padding: 0.75rem;
            border-radius: 4px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 1.2rem;
            transition: background 0.2s;
        }
        .register-btn:hover {
            background: #689f38;
        }
        .login-link {
            color: #fff;
            text-align: center;
            margin-top: 0.5rem;
            font-size: 1rem;
        }
        .login-link a {
            background: #222;
            color: #fff;
            padding: 0.2em 0.8em;
            border-radius: 3px;
            border: 1px solid #fff;
            margin-left: 0.5em;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s, color 0.2s;
        }
        .login-link a:hover {
            background: #fff;
            color: #222;
        }
        .alert {
            background: #e6f9e6;
            color: #388e3c;
            border: 1px solid #7cb342;
            border-radius: 4px;
            padding: 0.7em 1em;
            margin-bottom: 1em;
            text-align: center;
            font-size: 1rem;
        }
        .input-error {
            color: #ffbaba;
            font-size: 13px;
            margin-bottom: 10px;
        }
        @media (max-width: 500px) {
            .register-form { min-width: 90vw; max-width: 98vw; padding: 1.5rem 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-title">ĐĂNG KÝ TÀI KHOẢN</div>
        <div class="register-form">
            <div class="form-title">Tạo Tài Khoản Của Bạn</div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="input-group">
                    <input id="name" type="text" class="input-field" name="name" value="{{ old('name') }}" placeholder="Họ và Tên" required autofocus autocomplete="name">
                    <span class="input-icon">&#128100;</span>
                </div>
                @error('name')
                    <div class="input-error">{{ $message }}</div>
                @enderror

                <div class="input-group">
                    <input id="email" type="email" class="input-field" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="username">
                    <span class="input-icon">&#9993;</span>
                </div>
                @error('email')
                    <div class="input-error">{{ $message }}</div>
                @enderror

                <div class="input-group">
                    <input id="password" type="password" class="input-field" name="password" placeholder="Password" required autocomplete="new-password">
                    <span class="input-icon">&#128274;</span>
                </div>
                @error('password')
                    <div class="input-error">{{ $message }}</div>
                @enderror

                <div class="input-group">
                    <input id="password_confirmation" type="password" class="input-field" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                    <span class="input-icon">&#128274;</span>
                </div>
                @error('password_confirmation')
                    <div class="input-error">{{ $message }}</div>
                @enderror

                <button type="submit" class="register-btn">Đăng Ký</button>

                <div class="login-link">
                    Already have an account?
                    <a href="{{ route('login') }}">Đăng Nhập</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>