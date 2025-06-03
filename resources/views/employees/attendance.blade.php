<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chấm công bằng khuôn mặt</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .container { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 2rem; text-align: center; }
        #video { width: 100%; border-radius: 8px; margin-bottom: 1rem; }
        #captureBtn, #submitBtn { background: #4f8cff; color: #fff; border: none; padding: 0.7em 2em; border-radius: 4px; font-size: 1rem; margin: 0.5em 0; cursor: pointer; }
        #captureBtn:disabled, #submitBtn:disabled { background: #ccc; }
        #preview { margin: 1em 0; }
    </style>
</head>
<body>
<div class="container">
    <h2>Chấm công bằng khuôn mặt</h2>
    <video id="video" autoplay playsinline></video>
    <canvas id="canvas" style="display:none;"></canvas>
    <div id="preview"></div>
    <button id="captureBtn">Chụp ảnh & Chấm công</button>
    <form id="attendanceForm" method="POST" action="{{ url('/face-compare') }}" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="image1" id="image1">
        <!-- Truyền đường dẫn vật lý avatar của user hiện tại -->
        <input type="hidden" name="avatar_path" id="avatar_path" value="{{ Auth::user()->avatar ? storage_path('app/public/' . Auth::user()->avatar) : '' }}">
        <button type="submit" id="submitBtn">Gửi chấm công</button>
    </form>
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
        preview.innerHTML = '<img src="' + dataURL + '" style="width:100%;border-radius:8px;">';
        image1Input.value = dataURL;
        attendanceForm.style.display = 'block';
        captureBtn.disabled = true;
    };
</script>
</body>
</html>