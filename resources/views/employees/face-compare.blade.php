<!DOCTYPE html>
<html>
<head>
    <title>So sánh 2 ảnh khuôn mặt</title>
</head>
<body>
<h1>So sánh 2 ảnh khuôn mặt</h1>

<form method="POST" action="{{ url('/face-compare') }}" enctype="multipart/form-data">
    @csrf
    <label>Ảnh 1:</label><br>
    <input type="file" name="image1" required><br><br>

    <label>Ảnh 2:</label><br>
    <input type="file" name="image2" required><br><br>

    <button type="submit">So sánh</button>
</form>

@if(isset($result))
    <h2>Kết quả:</h2>
    @if(isset($result['confidence']))
        <p>Điểm giống nhau (confidence): <strong>{{ $result['confidence'] }}</strong></p>
    @else
        <p>Lỗi: {{ $result['error_message'] ?? 'Không thể so sánh' }}</p>
    @endif
@endif
</body>
</html>
