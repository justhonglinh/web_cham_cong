<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOvertimeController extends Controller
{
    public function management(Request $request)
    {
        $managerId = Auth::user()->id;
        $overtimeShifts = OvertimeShift::where('user_id', $managerId)->withCount('overtimeRequests')->get();
        $overtimeRequests = OvertimeRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with(['user', 'overtimeShift'])->orderByDesc('created_at')->paginate(20);
        return response()->json(['shifts' => $overtimeShifts, 'requests' => $overtimeRequests]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'start_time'        => 'required|date_format:H:i:s',
            'end_time'          => 'required|date_format:H:i:s',
            'date'              => 'required|date',
            'max_registrations' => 'nullable|integer|min:1',
        ]);

        $shift = OvertimeShift::create([
            'user_id'           => Auth::id(),
            'name'              => $request->name,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'date'              => $request->date,
            'max_registrations' => $request->max_registrations,
        ]);

        return response()->json(['message' => 'Tạo ca thêm giờ thành công', 'shift' => $shift], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => 'sometimes|required|string|max:255',
            'start_time'        => 'sometimes|required|date_format:H:i:s',
            'end_time'          => 'sometimes|required|date_format:H:i:s',
            'date'              => 'sometimes|required|date',
            'max_registrations' => 'nullable|integer|min:1',
        ]);

        $shift = OvertimeShift::findOrFail($id);
        $shift->update($request->only(['name', 'start_time', 'end_time', 'date', 'max_registrations']));

        return response()->json(['message' => 'Cập nhật thành công', 'shift' => $shift]);
    }

    public function destroy($id)
    {
        $shift = OvertimeShift::findOrFail($id);
        $shift->delete();
        return response()->json(['message' => 'Xóa thành công']);
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $overtimeRequest = OvertimeRequest::findOrFail($id);
        $overtimeRequest->status = $request->status;
        $overtimeRequest->save();
        return response()->json(['message' => 'Cập nhật thành công', 'request' => $overtimeRequest]);
    }

    public function employeeIndex()
    {
        $user = Auth::user();
        $managerId = $user->manager;
        $shifts = OvertimeShift::where('user_id', $managerId)
            ->where('date', '>=', now()->toDateString())
            ->withCount('requests')
            ->get();
        $registeredIds = OvertimeRequest::where('user_id', $user->id)->pluck('overtime_shift_id')->toArray();
        return response()->json(['shifts' => $shifts, 'registeredIds' => $registeredIds]);
    }

    public function register(Request $request, $shiftId)
    {
        $user = Auth::user();
        $existing = OvertimeRequest::where('user_id', $user->id)->where('overtime_shift_id', $shiftId)->first();
        if ($existing) return response()->json(['message' => 'Đã đăng ký rồi'], 422);
        OvertimeRequest::create(['user_id' => $user->id, 'overtime_shift_id' => $shiftId, 'status' => 'pending']);
        return response()->json(['message' => 'Đăng ký thành công'], 201);
    }

    public function unregister(Request $request, $shiftId)
    {
        $user = Auth::user();
        $record = OvertimeRequest::where('user_id', $user->id)
            ->where('overtime_shift_id', $shiftId)
            ->firstOrFail();
        $record->delete();
        return response()->json(['message' => 'Hủy đăng ký thành công']);
    }
}
