<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Shift;
use function PHPUnit\Framework\isEmpty;

class AttendanceController extends Controller
{
    public function show(Request $request)
    {
        $managerId = Auth::user()->id;
        $today = now()->toDateString();

        // Lấy danh sách nhân viên dưới quyền manager
        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerId)
            ->pluck('id');

        foreach ($employeeIds as $employeeId) {
            // Kiểm tra Attendance ngày hôm nay của từng nhân viên
            $attendance = Attendance::where('user_id', $employeeId)
                ->where('date', $today)
                ->first();

            if (empty($attendance)) {
                // Nếu chưa có bản ghi Attendance ngày hôm nay thì insert mới
                Attendance::create([
                    'user_id' => $employeeId,
                    'date' => $today,
                    'shift_id' => '1',
                    'check_in' => null,
                    'check_out' => null,
                    'status' => 'absent', // trạng thái mặc định
                ]);
            }
        }

        // Lấy lại danh sách Attendance sau khi đảm bảo đã có bản ghi ngày hôm nay
        $attendance_shifts = Attendance::with('shift', 'user')
            ->whereIn('user_id', $employeeIds)
            ->whereNull('overtime_id') // Lọc những bản ghi không có overtime
            ->where('date', $today)
            ->paginate(10);

        $attendance_overtimes = Attendance::with('overtimeShift', 'user')
            ->whereIn('user_id', $employeeIds)
            ->whereNull('shift_id')
            ->where('date', $today)
            ->paginate(10);

        $shifts = Shift::all();

        return view('attendance_management', compact('attendance_shifts','attendance_overtimes','shifts'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->shift_id = $request->shift_id;
        // Lưu bản ghi
        $attendance->save();

        return redirect()->back()->with('success', 'Ca làm đã được cập nhật!');
    }

}
