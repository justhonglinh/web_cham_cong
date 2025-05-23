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
        // lấy tên quản lý
        $managerName = Auth::user()->name;

        // lấy tên nhân viên dưới quản lý
        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerName)
            ->pluck('id');

        $overtimeRequests = OvertimeRequest::with('user', 'overtimeShift')
            ->whereIn('user_id', $employeeIds)
            ->orderBy('created_at', 'asc')   // theo thứ tự từ cũ nhất đến mới nhất
            ->paginate(10);

        return view('overtime_management', compact('overtimeRequests'));
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
