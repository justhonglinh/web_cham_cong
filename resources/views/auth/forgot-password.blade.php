<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            min-height: 600px;
        }

        .illustration-section {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ecff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .illustration {
            text-align: center;
            max-width: 300px;
        }

        .person {
            width: 120px;
            height: 120px;
            background: #ff6b6b;
            border-radius: 50%;
            margin: 0 auto 20px;
            position: relative;
            overflow: hidden;
        }

        .person::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 35px;
            width: 50px;
            height: 50px;
            background: #ffeaa7;
            border-radius: 50%;
        }

        .person::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25px;
            width: 70px;
            height: 40px;
            background: white;
            border-radius: 20px 20px 0 0;
        }

        .plant {
            width: 80px;
            height: 100px;
            background: #ff6b6b;
            border-radius: 50px;
            margin: 20px auto;
            position: relative;
        }

        .plant::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 20px;
            width: 40px;
            height: 60px;
            background: #74b9ff;
            border-radius: 20px 20px 0 0;
        }

        .leaves {
            position: absolute;
            top: -40px;
            left: 15px;
            width: 50px;
            height: 30px;
        }

        .leaf {
            position: absolute;
            width: 20px;
            height: 30px;
            background: #74b9ff;
            border-radius: 50% 0;
        }

        .leaf:nth-child(1) {
            transform: rotate(-20deg);
            left: 5px;
        }

        .leaf:nth-child(2) {
            transform: rotate(20deg);
            right: 5px;
        }

        .leaf:nth-child(3) {
            transform: rotate(-40deg);
            left: 0;
            top: 10px;
        }

        .leaf:nth-child(4) {
            transform: rotate(40deg);
            right: 0;
            top: 10px;
        }

        .form-section {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 2.5rem;
            color: #6c63ff;
            font-weight: 600;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .form-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .status-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #6c63ff;
            background: white;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 8px;
        }

        .reset-button {
            background: linear-gradient(135deg, #6c63ff 0%, #5a52ff 100%);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 99, 255, 0.3);
        }

        .reset-button:active {
            transform: translateY(0);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #6c63ff;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                margin: 10px;
            }
            
            .illustration-section {
                padding: 30px;
                min-height: 200px;
            }
            
            .form-section {
                padding: 40px 30px;
            }
            
            .form-title {
                font-size: 2rem;
            }
        }

        /* Animation for illustration */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .illustration {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Illustration Section -->
        <div class="illustration-section">
            <div class="illustration">
                <div class="person"></div>
                <div class="plant">
                    <div class="leaves">
                        <div class="leaf"></div>
                        <div class="leaf"></div>
                        <div class="leaf"></div>
                        <div class="leaf"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <h1 class="form-title">Quên<br>Mật Khẩu?</h1>
            <p class="form-subtitle">
                Quên mật khẩu? Không vấn đề gì. Chỉ cần cho chúng tôi biết địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn một liên kết đặt lại mật khẩu cho phép bạn chọn một cái mới.
            </p>

            <!-- Session Status (Laravel style) -->
            <div class="status-message" style="display: none;" id="statusMessage">
                Đã gửi liên kết đặt lại mật khẩu! Kiểm tra email của bạn.
            </div>

            <form id="resetForm" method="POST">
                <!-- CSRF Token (Laravel) -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        placeholder="Nhập địa chỉ email của bạn"
                        required 
                        autofocus 
                    />
                    <div class="error-message" id="emailError" style="display: none;">
                        Vui lòng nhập địa chỉ email hợp lệ.
                    </div>
                </div>

                <div>
                    <button type="submit" class="reset-button">
                        Đặt Lại Mật Khẩu
                    </button>
                </div>
            </form>

            <div class="back-link">
                <a href="#" onclick="history.back()">← Quay Lại Đăng Nhập</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const statusMessage = document.getElementById('statusMessage');
            
            // Simple email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                emailError.style.display = 'block';
                return;
            } else {
                emailError.style.display = 'none';
            }
            
            // Simulate form submission
            statusMessage.style.display = 'block';
            statusMessage.textContent = 'Password reset link sent to ' + email;
            
            // In real Laravel app, this form would submit to {{ route('password.email') }}
            console.log('Form would submit to Laravel backend');
        });

        // Real-time email validation
        document.getElementById('email').addEventListener('input', function() {
            const emailError = document.getElementById('emailError');
            emailError.style.display = 'none';
        });
    </script>
</body>
</html>