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
        $managerName = Auth::user()->name;
        $today = now()->toDateString();

        // Lấy danh sách nhân viên dưới quyền manager
        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerName)
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
        $attendances = Attendance::with('shift', 'user')
            ->whereIn('user_id', $employeeIds)
            ->where('date', $today)
            ->paginate(10);

        return view('attendence_management', compact('attendances'));
    }
}
