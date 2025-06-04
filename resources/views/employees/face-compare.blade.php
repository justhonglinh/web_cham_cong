<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả chấm công bằng khuôn mặt</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px #0002; padding: 2.5rem; text-align: center; }
        .avatar-preview { display: flex; justify-content: center; gap: 2rem; margin-bottom: 1.5rem; }
        .avatar-preview img { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; border: 2px solid #eee; }
        .avatar-label { font-size: 1rem; margin-bottom: 0.5em; font-weight: 500; }
        .result-box { margin-top: 2em; padding: 1.5em; border-radius: 8px; background: #f0f8ff; }
        .score { font-size: 1.5rem; font-weight: bold; color: #27ae60; }
        .fail { color: #e74c3c; }
        .btn-back {
            background: #4f8cff;
            color: #fff;
            border: none;
            padding: 0.7em 2em;
            border-radius: 4px;
            font-size: 1rem;
            margin-top: 1.5em;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-block;
            text-decoration: none;
        }
        .btn-back:hover { background: #2563eb; }
    </style>
</head>
<body>
<div class="container">
    <h1 style="color:#4f8cff; margin-bottom:1.5rem;">Kết quả chấm công bằng khuôn mặt</h1>

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
        <h2 style="margin-bottom:1em;">Kết quả:</h2>
        @if(isset($confidence))
            @if($confidence >= 70)
                <div class="score">✅ Chấm công thành công!<br>Điểm: {{ number_format($confidence, 2) }}</div>
            @else
                <div class="score fail">❌ Chấm công thất bại!<br>Điểm: {{ number_format($confidence, 2) }}</div>
            @endif
        @else
            <div class="score fail">Không nhận diện được khuôn mặt!</div>
        @endif
    </div>
    <a href="{{ route('dashboard') }}" class="btn-back">Quay lại Dashboard</a>
</div>
</body>
</html>