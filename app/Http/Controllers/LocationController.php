<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    // Lấy danh sách vị trí của user hiện tại
    public function index()
    {
        $locations = Location::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    // Lấy thông tin một vị trí cụ thể
    public function show($id)
    {
        $location = Location::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy vị trí'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }

    // Lưu vị trí mới
    public function store(Request $request)
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
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Tắt tất cả vị trí active hiện tại trước khi tạo mới
            Location::where('user_id', Auth::id())
                ->where('is_active', true)
                ->update(['is_active' => false]);

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
                'message' => 'Vị trí đã được lưu thành công',
                'data' => $location
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu vị trí',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Cập nhật vị trí
    public function update(Request $request, $id)
    {
        $location = Location::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy vị trí'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:10|max:1000',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $location->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Vị trí đã được cập nhật thành công',
                'data' => $location
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật vị trí',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Xóa vị trí
    public function destroy($id)
    {
        $location = Location::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy vị trí'
            ], 404);
        }

        try {
            $location->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vị trí đã được xóa thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa vị trí',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Toggle trạng thái active/inactive
    public function toggleStatus($id)
    {
        $location = Location::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy vị trí'
            ], 404);
        }

        try {
            $location->update([
                'is_active' => !$location->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái vị trí đã được cập nhật',
                'data' => $location
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Kiểm tra vị trí hiện tại có trong phạm vi cho phép không
    public function checkLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Tọa độ không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $locations = Location::where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        $validLocations = [];
        foreach ($locations as $location) {
            if ($location->isWithinRadius($request->latitude, $request->longitude)) {
                $validLocations[] = [
                    'id' => $location->id,
                    'name' => $location->name,
                    'address' => $location->address,
                    'distance' => round($location->distanceTo($request->latitude, $request->longitude), 2)
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $validLocations,
            'message' => count($validLocations) > 0 ? 'Có vị trí hợp lệ' : 'Không có vị trí hợp lệ'
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
            return $this->store($request);
        }

        // Tìm vị trí active hiện tại của user
        $currentLocation = Location::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        // Tắt tất cả vị trí active hiện tại
        Location::where('user_id', Auth::id())
            ->where('is_active', true)
            ->update(['is_active' => false]);

        if (!$currentLocation) {
            // Nếu không có vị trí active, tạo mới
            return $this->store($request);
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