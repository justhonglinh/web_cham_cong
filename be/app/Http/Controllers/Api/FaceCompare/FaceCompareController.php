<?php

namespace App\Http\Controllers\Api\FaceCompare;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FaceCompareController extends Controller
{
    public function compare(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!$user->avatar) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ảnh mẫu nhân viên',
            ], 422);
        }

        $avatarPath = storage_path('app/public/' . $user->avatar);
        if (!file_exists($avatarPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy file ảnh mẫu',
            ], 422);
        }

        $image1 = str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,', ' '], ['', '', '+'], $request->image);
        $image1Path = storage_path('app/temp_face_' . $user->id . '.png');
        file_put_contents($image1Path, base64_decode($image1));

        $image2Path = storage_path('app/temp_avatar_' . $user->id . '.png');
        copy($avatarPath, $image2Path);

        try {
            $response = Http::asMultipart()->post('https://api-us.faceplusplus.com/facepp/v3/compare', [
                'api_key'     => config('services.facepp.key'),
                'api_secret'  => config('services.facepp.secret'),
                'image_file1' => fopen($image1Path, 'r'),
                'image_file2' => fopen($image2Path, 'r'),
            ]);
        } finally {
            @unlink($image1Path);
            @unlink($image2Path);
        }

        $confidence = $response->json('confidence');

        if ($confidence === null) {
            return response()->json([
                'success' => false,
                'message' => 'Không nhận diện được khuôn mặt',
                'score'   => null,
            ]);
        }

        if ($confidence < 70) {
            return response()->json([
                'success' => false,
                'message' => 'Khuôn mặt không khớp',
                'score'   => $confidence,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Khuôn mặt khớp',
            'score'   => $confidence,
        ]);
    }
}
