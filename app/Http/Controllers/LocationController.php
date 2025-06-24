<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    // Lấy vị trí active hiện tại của user
    public function show()
    {
        $location = Location::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa có vị trí nào được lưu'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }

    // Cập nhật vị trí hiện tại (active location) hoặc tạo mới
    public function updateCurrent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:10|max:1000',
            'description' => 'nullable|string|max:1000',
            'action' => 'nullable|string|in:create,update', // Thêm tham số action
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $action = $request->input('action', 'update'); // Mặc định là update

        // Nếu action là create hoặc không có vị trí active, tạo mới
        if ($action === 'create') {
            try {
                $location = Location::create([
                    'user_id' => Auth::id(),
                    'name' => $request->name,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'radius' => $request->radius ?? 100,
                    'description' => $request->description,
                    'is_active' => true,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Thao tác thành công',
                    'data' => $location
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi lưu vị trí',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Tìm vị trí active hiện tại của user
        $currentLocation = Location::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$currentLocation) {
            // Nếu không có vị trí active, tạo mới
            try {
                $location = Location::create([
                    'user_id' => Auth::id(),
                    'name' => $request->name,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'radius' => $request->radius ?? 100,
                    'description' => $request->description,
                    'is_active' => true,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Thao tác thành công',
                    'data' => $location
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi lưu vị trí',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        try {
            $currentLocation->update([
                'name' => $request->name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius' => $request->radius ?? 100,
                'description' => $request->description,
                'is_active' => true, // Đảm bảo vị trí này được active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vị trí đã được cập nhật thành công',
                'data' => $currentLocation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật vị trí',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 