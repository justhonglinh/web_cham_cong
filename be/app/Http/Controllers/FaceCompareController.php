<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;

class FaceCompareController extends Controller
{
    public function showForm()
    {
        return view('employees.face-compare');
    }

    public function compare(Request $request)
    {
        $request->validate([
            'image1' => 'required|string',
            'avatar_path' => 'required|string',
        ]);

        // Lưu lại bản gốc để truyền cho view
        $capturedUrl = $request->input('image1');

        // Lưu ảnh chụp từ webcam (base64) thành file tạm
        $image1 = str_replace('data:image/png;base64,', '', $capturedUrl);
        $image1 = str_replace(' ', '+', $image1);
        $image1Path = storage_path('app/temp_image1.png');
        file_put_contents($image1Path, base64_decode($image1));

        // Lấy avatar từ ổ đĩa
        $avatarPath = $request->input('avatar_path');
        $image2Path = storage_path('app/temp_avatar.png');
        copy($avatarPath, $image2Path);

        $api_key = env('FACEPP_API_KEY');
        $api_secret = env('FACEPP_API_SECRET');
        $url = 'https://api-us.faceplusplus.com/facepp/v3/compare';

        $response = Http::asMultipart()->post($url, [
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'image_file1' => fopen($image1Path, 'r'),
            'image_file2' => fopen($image2Path, 'r'),
        ]);

        // Xóa file tạm
        @unlink($image1Path);
        @unlink($image2Path);

        $result = $response->json();

        // Chuẩn bị dữ liệu cho view
        $confidence = $result['confidence'] ?? null;
        $avatarUrl = asset('storage/' . str_replace(storage_path('app/public/'), '', $avatarPath));

        // Xử lý vị trí và khoảng cách
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $distance = $request->input('distance');

        // Tọa độ công ty (thay bằng tọa độ thực tế)
        $officeLat = 21.028511;
        $officeLng = 105.804817;

        // Nếu chưa có distance từ client thì tính lại (phòng trường hợp JS không gửi)
        if ($latitude && $longitude && !$distance) {
            $distance = $this->haversine($latitude, $longitude, $officeLat, $officeLng) * 1000; // m
        }

        // Lấy giờ bắt đầu ca làm (ví dụ, thực tế nên lấy từ DB)
        $shiftStart = '08:00:00';
        $now = now();

        // Xác định status đúng với enum trong migration
        if ($confidence === null || $confidence < 70 || ($distance !== null && $distance > 200)) {
            $status = 'absent';
        } elseif ($now->format('H:i:s') > $shiftStart) {
            $status = 'late';
        } else {
            $status = 'present';
        }

        // Lưu vào DB (nếu có model Attendance)
        if (Auth::check()) {
            Attendance::create([
                'user_id' => Auth::id(),
                'date' => $now->toDateString(),
                'check_in_time' => $now, // đúng tên cột migration
                'status' => $status,
                // Nếu muốn lưu thêm vị trí, khoảng cách thì thêm cột vào migration
                // 'latitude' => $latitude,
                // 'longitude' => $longitude,
                // 'distance' => $distance,
            ]);
        }

        return view('employees.face-compare', compact('avatarUrl', 'capturedUrl', 'confidence', 'status', 'distance'));
    }

    // Hàm tính khoảng cách Haversine (km)
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        return $distance;
    }

    // API so sánh khuôn mặt cho frontend gọi AJAX
    public function apiCompare(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        // Lấy user hiện tại
        $user = Auth::user();
        if (!$user || !$user->avatar) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh mẫu nhân viên']);
        }

        // Lưu ảnh chụp từ webcam (base64) thành file tạm
        $image1 = str_replace('data:image/jpeg;base64,', '', $request->input('image'));
        $image1 = str_replace('data:image/png;base64,', '', $image1);
        $image1 = str_replace(' ', '+', $image1);
        $image1Path = storage_path('app/temp_image1.png');
        file_put_contents($image1Path, base64_decode($image1));

        // Lấy avatar từ ổ đĩa
        $avatarPath = storage_path('app/public/' . $user->avatar);
        if (!file_exists($avatarPath)) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy file ảnh mẫu']);
        }
        $image2Path = storage_path('app/temp_avatar.png');
        copy($avatarPath, $image2Path);

        $api_key = env('FACEPP_API_KEY');
        $api_secret = env('FACEPP_API_SECRET');
        $url = 'https://api-us.faceplusplus.com/facepp/v3/compare';

        $response = Http::asMultipart()->post($url, [
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'image_file1' => fopen($image1Path, 'r'),
            'image_file2' => fopen($image2Path, 'r'),
        ]);

        // Xóa file tạm
        @unlink($image1Path);
        @unlink($image2Path);

        $result = $response->json();
        $confidence = $result['confidence'] ?? null;
        $threshold = 70; // Ngưỡng nhận diện thành công

        if ($confidence === null) {
            return response()->json([
                'success' => false,
                'message' => 'Không nhận diện được khuôn mặt',
                'score' => null
            ]);
        }
        if ($confidence < $threshold) {
            return response()->json([
                'success' => false,
                'message' => 'Khuôn mặt không khớp',
                'score' => $confidence
            ]);
        }
        // Trả về điểm số và message rõ ràng khi thành công
        return response()->json([
            'success' => true,
            'message' => 'Khuôn mặt khớp',
            'score' => $confidence
        ]);
    }
}