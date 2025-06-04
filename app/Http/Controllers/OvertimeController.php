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
        $managerId = Auth::user()->id;

        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerId)
            ->pluck('id');

        if ($employeeIds->isEmpty()) {
            // Nếu không có nhân viên dưới quyền, trả về dữ liệu trống
            $overtimeShifts = collect(); // hoặc có thể là paginate rỗng, tùy bạn xử lý view
        } else {
            $overtimeShifts = OvertimeShift::with(['overtimeRequests' => function ($query) use ($employeeIds) {
                $query->whereIn('user_id', $employeeIds)
                    ->with('user')
                    ->orderBy('created_at', 'asc');
            }])->paginate(10);
        }

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
            'user_id' => 'required|exists:users,id',
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
