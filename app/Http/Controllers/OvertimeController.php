<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function show()
    {
        $managerName = Auth::user()->name;

        // Lấy danh sách id nhân viên dưới quyền quản lý
        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerName)
            ->pluck('id');

        // Lấy ca làm thêm giờ, kèm các yêu cầu của nhân viên dưới quyền, cùng user info trong yêu cầu
        $overtimeShifts = OvertimeShift::with(['overtimeRequests' => function ($query) use ($employeeIds) {
            $query->whereIn('user_id', $employeeIds)
                ->with('user')  // eager load thông tin user trong yêu cầu
                ->orderBy('created_at', 'asc');
        }])
            ->paginate(10);

        return view('overtime_management', compact('overtimeShifts'));
    }

    // Lưu overtime mới (từ modal Create)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        OvertimeShift::create([
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
        ]);

        return redirect()->route('shifts.index')->with('success', 'Thêm làm thêm giờ thành công!');
    }

    // Cập nhật overtime (từ modal Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        $overtime = OvertimeShift::findOrFail($id);
        $overtime->update([
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
        ]);

        return redirect()->route('overtimes.index')->with('success', 'Cập nhật làm thêm giờ thành công!');
    }

    // Xóa overtime
    public function destroy($id)
    {
        $overtime = OvertimeShift::findOrFail($id);
        $overtime->delete();

        return redirect()->route('overtimes.index')->with('success', 'Xóa làm thêm giờ thành công!');
    }
}
