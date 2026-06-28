<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiShiftController extends Controller
{
    /**
     * Lấy danh sách ca làm việc của manager hiện tại.
     */
    public function index()
    {
        $managerId = Auth::user()->id;

        $shifts = Shift::where('user_id', $managerId)
            ->withCount('attendances')
            ->get();

        return response()->json($shifts);
    }

    /**
     * Tạo mới ca làm việc.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $shift = Shift::create([
            'name'       => $request->name,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'user_id'    => Auth::user()->id,
        ]);

        return response()->json([
            'message' => 'Tạo ca làm việc thành công.',
            'shift'   => $shift,
        ], 201);
    }

    /**
     * Cập nhật ca làm việc.
     */
    public function update(Request $request, $id)
    {
        $managerId = Auth::user()->id;

        $shift = Shift::where('id', $id)
            ->where('user_id', $managerId)
            ->firstOrFail();

        $request->validate([
            'name'       => 'sometimes|required|string|max:255',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time'   => 'sometimes|required|date_format:H:i|after:start_time',
        ]);

        $shift->update($request->only(['name', 'start_time', 'end_time']));

        return response()->json([
            'message' => 'Cập nhật ca làm việc thành công.',
            'shift'   => $shift,
        ]);
    }

    /**
     * Xóa ca làm việc.
     */
    public function destroy($id)
    {
        $managerId = Auth::user()->id;

        $shift = Shift::where('id', $id)
            ->where('user_id', $managerId)
            ->firstOrFail();

        $today = now()->toDateString();

        // Không cho phép xóa ca đang được dùng cho ngày hiện tại hoặc tương lai
        $hasCurrentAttendance = Attendance::where('shift_id', $shift->id)
            ->where('date', '>=', $today)
            ->exists();

        if ($hasCurrentAttendance) {
            return response()->json([
                'message' => 'Không thể xóa ca làm đang được sử dụng cho các ngày hiện tại hoặc tương lai.',
            ], 422);
        }

        $shift->delete();

        return response()->json([
            'message' => 'Xóa ca làm việc thành công.',
        ]);
    }
}
