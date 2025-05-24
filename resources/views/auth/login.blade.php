<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Existing Login Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/custom-auth.css') }}">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1500&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-title {
            color: #fff;
            font-size: 2.5rem;
            letter-spacing: 0.2em;
            margin-bottom: 2rem;
            margin-top: 2rem;
            text-shadow: 0 2px 8px #0008;
        }
        .login-form {
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
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            color: #fff;
            font-size: 0.95rem;
        }
        .remember-me input[type="checkbox"] {
            accent-color: #7cb342;
            margin-right: 0.3em;
        }
        .forgot-password {
            color: #fff;
            text-decoration: underline;
            font-size: 0.95rem;
        }
        .login-btn {
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
        .login-btn:hover {
            background: #689f38;
        }
        .register-link {
            color: #fff;
            text-align: center;
            margin-top: 0.5rem;
            font-size: 1rem;
        }
        .register-link a {
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
        .register-link a:hover {
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
        @media (max-width: 500px) {
            .login-form { min-width: 90vw; max-width: 98vw; padding: 1.5rem 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">QUẢN LÝ CHẤM CÔNG</div>
        <div class="login-form">
            <div class="form-title">ĐĂNG NHẬP TẠI ĐÂY</div>
            @if (session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group">
                    <input type="email" class="input-field" name="email" placeholder="EMAIL" required value="{{ old('email') }}">
                    <span class="input-icon">&#9993;</span>
                </div>
                @error('email')
                    <div style="color: #ffbaba; font-size: 13px; margin-bottom: 10px;">{{ $message }}</div>
                @enderror

                <div class="input-group">
                    <input type="password" class="input-field" name="password" placeholder="PASSWORD" required>
                    <span class="input-icon">&#128274;</span>
                </div>
                @error('password')
                    <div style="color: #ffbaba; font-size: 13px; margin-bottom: 10px;">{{ $message }}</div>
                @enderror

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ghi nhớ đăng nhập
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">Quên mật khẩu?</a>
                    @endif
                </div>

                <button type="submit" class="login-btn">Đăng Nhập</button>

                <div class="register-link">
                    Để đăng ký tài khoản mới →
                    <a href="{{ route('register') }}">Nhấn vào đây</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>