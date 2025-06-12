<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Facades\Redirect;
use App\Models\OvertimeShift;

class AttendanceController extends Controller
{
    // Hiển thị danh sách chấm công cho manager
    public function show(Request $request)
    {
        $managerId = Auth::user()->id;
        $today = now()->toDateString();

        // Lấy danh sách nhân viên dưới quyền manager
        $users = User::where('role', 'employee')
            ->where('manager', $managerId)
            ->get();

        $employeeIds = $users->pluck('id');

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
                    'created_at' => now(),
                ]);
            }
        }

        // Lấy lại danh sách Attendance sau khi đảm bảo đã có bản ghi ngày hôm nay
        $attendance_shifts = Attendance::with('shift', 'user')
            ->whereIn('user_id', $employeeIds)
            ->whereNull('overtime_id') // Lọc những bản ghi không có overtime
            ->where('date', $today)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $attendance_overtimes = Attendance::with('overtimeShift', 'user')
            ->whereIn('user_id', $employeeIds)
            ->whereNull('shift_id')
            ->where('date', $today)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $shifts = Shift::all();

        return view('attendance_management', compact('attendance_shifts', 'attendance_overtimes', 'shifts', 'users'));
    }

    // Cập nhật ca làm cho attendance
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->shift_id = $request->shift_id;
        $attendance->save();

        return Redirect::route('attendance.index')->with('success', 'Ca làm đã được cập nhật!');
    }

    // Lịch sử chấm công cho nhân viên
    public function history()
    {
        $user = Auth::user();
        $attendances = Attendance::where('user_id', $user->id)
            ->orderByDesc('date')
            ->paginate(10);

        return view('employees.attendance-history', compact('attendances'));
    }

    public function index(Request $request)
    {
        $query = Attendance::with(['user', 'shift'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        $overtimeQuery = AttendanceOvertime::with(['user', 'shift'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // Xử lý tìm kiếm cho bảng ca làm việc
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Xử lý tìm kiếm cho bảng tăng ca
        if ($request->filled('search_overtime')) {
            $search = $request->search_overtime;
            $overtimeQuery->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Lọc theo ngày
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
            $overtimeQuery->whereDate('date', $request->date);
        }

        // Lọc theo ca làm việc
        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
            $overtimeQuery->where('shift_id', $request->shift_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $overtimeQuery->where('status', $request->status);
        }

        $attendance = $query->paginate(10);
        $attendance_overtimes = $overtimeQuery->paginate(10);

        // Thêm tham số table vào URL phân trang
        if ($request->filled('table')) {
            $attendance->appends(['table' => $request->table]);
            $attendance_overtimes->appends(['table' => $request->table]);
        }

        $shifts = Shift::all();
        $users = User::all();

        return view('attendance_management', compact('attendance', 'attendance_overtimes', 'shifts', 'users'));
    }

    public function edit(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success', 'Cập nhật thông tin chấm công thành công');
    }
}
