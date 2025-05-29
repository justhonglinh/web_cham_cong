<?php

namespace App\Http\Controllers;

use App\Models\OvertimeShift;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user->role === 'manager') {
            // Manager xem tất cả ca OT và trạng thái đăng ký của nhân viên dưới quyền
            $employeeIds = User::where('role', 'employee')
                ->where('manager', $user->name)
                ->pluck('id');

            $overtimeShifts = OvertimeShift::with(['overtimeRequests' => function ($query) use ($employeeIds) {
                $query->whereIn('user_id', $employeeIds)
                    ->with('user')
                    ->orderBy('created_at', 'asc');
            }])->orderBy('date', 'desc')->paginate(10);
        } else {
            // Nhân viên xem tất cả ca OT, kèm trạng thái đăng ký của chính mình
            $overtimeShifts = OvertimeShift::with(['overtimeRequests' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->with('user');
            }])->orderBy('date', 'desc')->paginate(10);
        }

        return view('overtime_management', compact('overtimeShifts'));
    }

    // Lưu ca OT mới (chỉ cho manager)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
            'max_registrations' => 'nullable|integer|min:1',
        ]);

        OvertimeShift::create([
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'max_registrations' => $request->max_registrations,
        ]);

        return redirect()->route('overtime.index')->with('success', 'Tạo ca OT thành công!');
    }

    // Cập nhật ca OT (chỉ cho manager)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
            'max_registrations' => 'nullable|integer|min:1',
        ]);

        $overtime = OvertimeShift::findOrFail($id);
        $overtime->update([
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'max_registrations' => $request->max_registrations,
        ]);

        return redirect()->route('overtime.index')->with('success', 'Cập nhật ca OT thành công!');
    }

    // Xóa ca OT (chỉ cho manager)
    public function destroy($id)
    {
        $overtime = OvertimeShift::findOrFail($id);
        $overtime->delete();

        return redirect()->route('overtime.index')->with('success', 'Xóa ca OT thành công!');
    }
}