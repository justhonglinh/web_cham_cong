<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chấm công bằng khuôn mặt</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #fdf2f8 100%);
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 420px;
            margin: 48px auto;
            background: rgba(255,255,255,0.95);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 #a5b4fc55, 0 1.5px 8px #f472b655;
            padding: 2.5rem 2rem 2rem 2rem;
            text-align: center;
            position: relative;
        }
        .container::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 1.5rem;
            box-shadow: 0 0 0 4px #a5b4fc33;
            z-index: 0;
            pointer-events: none;
        }
        h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }
        #video {
            width: 100%;
            border-radius: 1rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 4px 16px #818cf833;
            border: 2px solid #a5b4fc;
        }
        #captureBtn, #submitBtn {
            background: linear-gradient(90deg, #6366f1 0%, #a21caf 100%);
            color: #fff;
            border: none;
            padding: 0.8em 2.2em;
            border-radius: 2em;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0.7em 0;
            cursor: pointer;
            box-shadow: 0 2px 8px #818cf855;
            transition: background 0.2s, transform 0.2s;
        }
        #captureBtn:hover, #submitBtn:hover {
            background: linear-gradient(90deg, #a21caf 0%, #6366f1 100%);
            transform: translateY(-2px) scale(1.04);
        }
        #captureBtn:disabled, #submitBtn:disabled {
            background: #d1d5db;
            color: #888;
            cursor: not-allowed;
            box-shadow: none;
        }
        #preview {
            margin: 1.2em 0;
        }
        #preview img {
            width: 100%;
            border-radius: 1rem;
            box-shadow: 0 2px 8px #818cf855;
            border: 2px solid #a5b4fc;
        }
        form {
            margin-top: 0.5em;
        }
        .step-label {
            display: inline-block;
            background: linear-gradient(90deg, #6366f1 0%, #a21caf 100%);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 1em;
            padding: 0.3em 1.2em;
            margin-bottom: 1.2em;
            letter-spacing: 0.5px;
            box-shadow: 0 1px 4px #818cf833;
        }
        .note {
            color: #a21caf;
            font-size: 0.95rem;
            margin-top: 1.2em;
            background: #fdf2f8;
            border-radius: 0.7em;
            padding: 0.7em 1em;
            box-shadow: 0 1px 4px #f472b655;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="step-label">Đưa khuôn mặt vào khung camera</div>
    <h2>Chấm công bằng khuôn mặt</h2>
    <video id="video" autoplay playsinline></video>
    <canvas id="canvas" style="display:none;"></canvas>
    <div id="preview"></div>
    <button id="captureBtn">📸 Chụp ảnh & Chấm công</button>
    <form id="attendanceForm" method="POST" action="{{ url('/face-compare') }}" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="image1" id="image1">
        <input type="hidden" name="avatar_path" id="avatar_path" value="{{ Auth::user()->avatar ? storage_path('app/public/' . Auth::user()->avatar) : '' }}">
        <button type="submit" id="submitBtn">🚀 Gửi chấm công</button>
    </form>
    <div class="note">
        <b>Lưu ý:</b> Đảm bảo khuôn mặt rõ nét, không bị ngược sáng hoặc che khuất để tăng độ chính xác nhận diện.
    </div>
</div>
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const preview = document.getElementById('preview');
    const attendanceForm = document.getElementById('attendanceForm');
    const image1Input = document.getElementById('image1');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => { video.srcObject = stream; })
        .catch(err => { alert('Không thể truy cập camera!'); });

    captureBtn.onclick = function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/png');
        preview.innerHTML = '<img src="' + dataURL + '" alt="Ảnh chụp">';
        image1Input.value = dataURL;
        attendanceForm.style.display = 'block';
        captureBtn.disabled = true;
        video.style.display = 'none'; // Ẩn camera sau khi chụp
    };
</script>
</body>
</html>