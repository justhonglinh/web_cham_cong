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
                ->with('user')                  // eager load user
                ->orderBy('created_at', 'asc');
        }])->paginate(10);

        return view('overtime_management', compact('overtimeShifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'max_registrations' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        OvertimeShift::create($request->all());

        return redirect()->route('overtime.index')->with('success', 'Thêm làm thêm giờ thành công!');
    }

    // Cập nhật overtime (từ modal Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'max_registrations' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $overtime = OvertimeShift::findOrFail($id);
        $overtime->update($request->all());

        return redirect()->route('overtime.index')->with('success', 'Cập nhật làm thêm giờ thành công!');
    }

    // Xóa overtime
    public function destroy($id)
    {
        $overtime = OvertimeShift::findOrFail($id);
        $overtime->delete();

        return redirect()->route('overtime.index')->with('success', 'Xóa làm thêm giờ thành công!');
    }
}
