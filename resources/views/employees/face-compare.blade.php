<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả chấm công bằng khuôn mặt</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #fdf2f8 100%);
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 520px;
            margin: 56px auto;
            background: rgba(255,255,255,0.98);
            border-radius: 2rem;
            box-shadow: 0 12px 40px 0 #a5b4fc55, 0 2px 12px #f472b655;
            padding: 2.8rem 2.2rem 2.2rem 2.2rem;
            text-align: center;
            position: relative;
        }
        h1 {
            color: #6366f1;
            font-size: 2.1rem;
            font-weight: 800;
            margin-bottom: 2rem;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #6366f1 0%, #a21caf 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .avatar-preview {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin-bottom: 2rem;
        }
        .avatar-preview img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 1.2rem;
            border: 3px solid #a5b4fc;
            box-shadow: 0 2px 12px #818cf855;
            background: #f3f4f6;
        }
        .avatar-label {
            font-size: 1.05rem;
            margin-bottom: 0.5em;
            font-weight: 600;
            color: #6366f1;
            letter-spacing: 0.5px;
        }
        .result-box {
            margin-top: 2.2em;
            padding: 2em 1.5em;
            border-radius: 1.2rem;
            background: linear-gradient(120deg, #e0e7ff 60%, #fdf2f8 100%);
            box-shadow: 0 2px 12px #a5b4fc22;
        }
        .result-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 1.2em;
            letter-spacing: 0.5px;
        }
        .score {
            font-size: 1.5rem;
            font-weight: bold;
            color: #22c55e;
            margin-bottom: 0.5em;
        }
        .fail {
            color: #e74c3c;
        }
        .score-icon {
            font-size: 2.2rem;
            vertical-align: middle;
            margin-right: 0.3em;
        }
        .btn-back {
            background: linear-gradient(90deg, #6366f1 0%, #a21caf 100%);
            color: #fff;
            border: none;
            padding: 0.85em 2.5em;
            border-radius: 2em;
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 2em;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            display: inline-block;
            text-decoration: none;
            box-shadow: 0 2px 8px #818cf855;
        }
        .btn-back:hover {
            background: linear-gradient(90deg, #a21caf 0%, #6366f1 100%);
            transform: translateY(-2px) scale(1.04);
        }
        @media (max-width: 600px) {
            .container { padding: 1.2rem 0.5rem; }
            .avatar-preview { flex-direction: column; gap: 1.2rem; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Kết quả chấm công bằng khuôn mặt</h1>
    <div class="avatar-preview">
        <div>
            <div class="avatar-label">Ảnh đại diện</div>
            <img src="{{ $avatarUrl ?? '' }}" alt="Avatar">
        </div>
        <div>
            <div class="avatar-label">Ảnh vừa chụp</div>
            <img src="{{ $capturedUrl ?? '' }}" alt="Ảnh chụp">
        </div>
    </div>
    <div class="result-box">
    <div class="result-title">Kết quả:</div>
    @if(isset($status))
        @if($status == 'present')
            <div class="score"><span class="score-icon">✅</span>Chấm công thành công!</div>
        @elseif($status == 'late')
            <div class="score" style="color:#f59e42"><span class="score-icon">⏰</span>Đi muộn!</div>
        @elseif($status == 'absent')
            <div class="score fail"><span class="score-icon">❌</span>Chấm công thất bại (vắng mặt)!</div>
        @elseif($status == 'leave')
            <div class="score" style="color:#3b82f6"><span class="score-icon">🏖️</span>Nghỉ phép!</div>
        @elseif($status == 'early_leave')
            <div class="score" style="color:#ec4899"><span class="score-icon">🏃‍♂️</span>Về sớm!</div>
        @else
            <div class="score fail"><span class="score-icon">⚠️</span>Không xác định trạng thái!</div>
        @endif
        @if(isset($confidence))
            <div style="font-size:1.1rem;color:#6366f1;font-weight:600;">Điểm nhận diện: {{ number_format($confidence, 2) }}</div>
        @endif
        @if(isset($distance))
            <div style="font-size:1.1rem;color:#6366f1;font-weight:600;">Khoảng cách tới công ty: {{ number_format($distance, 0) }} mét</div>
        @endif
    @else
        <div class="score fail"><span class="score-icon">⚠️</span>Không nhận diện được khuôn mặt!</div>
    @endif
</div>
    <a href="{{ route('dashboard') }}" class="btn-back">Quay lại Dashboard</a>
</div>
</body>
</html>