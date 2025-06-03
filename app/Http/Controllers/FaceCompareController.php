<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    // Lưu ảnh chụp từ webcam (base64) thành file tạm
    $image1 = $request->input('image1');
    $image1 = str_replace('data:image/png;base64,', '', $image1);
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

    $response = \Illuminate\Support\Facades\Http::asMultipart()->post($url, [
        'api_key' => $api_key,
        'api_secret' => $api_secret,
        'image_file1' => fopen($image1Path, 'r'),
        'image_file2' => fopen($image2Path, 'r'),
    ]);

    // Xóa file tạm
    @unlink($image1Path);
    @unlink($image2Path);

    $result = $response->json();
    return view('employees.face-compare', compact('result'));
}

}