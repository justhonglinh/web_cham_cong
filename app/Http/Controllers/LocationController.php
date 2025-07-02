<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    // Lấy vị trí active hiện tại của user
    public function show()
    {
        $location = Location::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }

    public function updateCurrent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:10|max:1000',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Dữ liệu không hợp lệ');
        }

            $userId = Auth::id();

            // Tìm vị trí active hiện tại của user
            $currentLocation = Location::where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            // Chuẩn bị dữ liệu location
            $locationData = [
                'user_id' => $userId,
                'name' => $request->name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius' => $request->radius ?? 100,
                'description' => $request->description,
                'is_active' => true,
            ];

            // Logic mới: Kiểm tra location có mapping với user hay không
            if ($currentLocation) {
                // Nếu user đã có location active -> UPDATE
                $currentLocation->update($locationData);
                $location = $currentLocation->fresh();
                $message = 'Vị trí đã được cập nhật thành công';
            } else {
                // Nếu user chưa có location active -> CREATE
                $location = Location::create($locationData);
                $message = 'Vị trí đã được tạo thành công';
            }

            return redirect()->back()->with('success', $message);

    }
} 