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
            'image1' => 'required|image|mimes:jpeg,png,bmp,gif|max:5120',
            'image2' => 'required|image|mimes:jpeg,png,bmp,gif|max:5120',
        ]);

        $api_key = env('FACEPP_API_KEY');
        $api_secret = env('FACEPP_API_SECRET');
        $url = 'https://api-us.faceplusplus.com/facepp/v3/compare';

        $response = Http::asMultipart()->post($url, [
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'image_file1' => fopen($request->file('image1')->getPathname(), 'r'),
            'image_file2' => fopen($request->file('image2')->getPathname(), 'r'),
        ]);

        $result = $response->json();
        return view('face-compare', compact('result'));
    }
}
