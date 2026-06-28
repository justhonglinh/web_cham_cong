<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiDashboardController extends Controller
{
    public function index()
    {
        $managerId = Auth::user()->id;
        $today = now()->toDateString();

        // Thống kê nhân viên
        $employeesCount = User::where('manager', $managerId)->count();

        // Thống kê ca làm việc chi tiết
        $allShifts = Shift::where('user_id', $managerId)->get();
        $shiftsCount = $allShifts->count();

        // Phân loại ca làm theo trạng thái
        $activeShifts = $allShifts->filter(function ($shift) use ($today) {
            return !Attendance::where('shift_id', $shift->id)
                ->where('date', '<', $today)
                ->exists();
        })->count();

        $oldShifts = $allShifts->filter(function ($shift) use ($today) {
            return Attendance::where('shift_id', $shift->id)
                ->where('date', '<', $today)
                ->exists();
        })->count();

        $unusedShifts = $allShifts->filter(function ($shift) {
            return !$shift->attendances()->exists();
        })->count();

        // Thống kê tăng ca
        $overtimesCount = OvertimeShift::where('user_id', $managerId)->count();

        $overtimeRequestsCount = OvertimeRequest::whereHas('user', fn ($q) => $q->where('manager', $managerId))->count();

        // Chấm công hôm nay
        $attendancesCount = Attendance::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->where('status', 'present')
            ->whereDate('created_at', $today)
            ->count();

        // Yêu cầu tăng ca chờ duyệt
        $pendingOvertimeRequests = OvertimeRequest::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->where('status', 'pending')
            ->count();

        // Yêu cầu nghỉ phép chờ duyệt
        $pendingLeaveRequests = LeaveRequest::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->where('status', 'pending')
            ->count();

        // Nhân viên đi muộn hôm nay (sau 8:15)
        $lateAttendances = Attendance::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->whereDate('date', $today)
            ->whereNotNull('check_in_time')
            ->where('check_in_time', '>', '08:15:00')
            ->count();

        // Yêu cầu tăng ca đã được phê duyệt
        $approvedOvertimeRequests = OvertimeRequest::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->where('status', 'approved')
            ->count();

        // Yêu cầu nghỉ phép đã được phê duyệt
        $approvedLeaveRequests = LeaveRequest::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->where('status', 'approved')
            ->count();

        // Các yêu cầu tăng ca gần đây
        $recentOvertimeRequests = OvertimeRequest::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->with('user', 'overtimeShift')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Các yêu cầu nghỉ phép gần đây
        $recentLeaveRequests = LeaveRequest::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Các chấm công gần đây
        $recentAttendances = Attendance::whereHas('user', fn ($q) => $q->where('manager', $managerId))
            ->with('user', 'shift')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Thống kê theo ngày trong tuần (7 ngày gần nhất)
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $count = Attendance::whereHas('user', fn ($q) => $q->where('manager', $managerId))
                ->whereDate('date', $date)
                ->where('status', 'present')
                ->count();

            $weeklyStats[] = [
                'date'  => Carbon::parse($date)->format('d/m'),
                'day'   => Carbon::parse($date)->format('D'),
                'count' => $count,
            ];
        }

        // Thống kê ca làm theo thời gian
        $shiftTimeStats = [];
        foreach ($allShifts as $shift) {
            $usageCount = $shift->attendances()->count();
            $shiftTimeStats[] = [
                'name'        => $shift->name,
                'start_time'  => $shift->start_time,
                'end_time'    => $shift->end_time,
                'usage_count' => $usageCount,
                'is_active'   => !Attendance::where('shift_id', $shift->id)
                    ->where('date', '<', $today)
                    ->exists(),
            ];
        }

        return response()->json([
            'employeesCount'           => $employeesCount,
            'shiftsCount'              => $shiftsCount,
            'activeShifts'             => $activeShifts,
            'oldShifts'                => $oldShifts,
            'unusedShifts'             => $unusedShifts,
            'overtimesCount'           => $overtimesCount,
            'approvedOvertimeRequests' => $approvedOvertimeRequests,
            'pendingOvertimeRequests'  => $pendingOvertimeRequests,
            'attendancesCount'         => $attendancesCount,
            'lateAttendances'          => $lateAttendances,
            'pendingLeaveRequests'     => $pendingLeaveRequests,
            'approvedLeaveRequests'    => $approvedLeaveRequests,
            'overtimeRequestsCount'    => $overtimeRequestsCount,
            'weeklyStats'              => $weeklyStats,
            'shiftTimeStats'           => $shiftTimeStats,
            'recentOvertimeRequests'   => $recentOvertimeRequests,
            'recentAttendances'        => $recentAttendances,
            'recentLeaveRequests'      => $recentLeaveRequests,
        ]);
    }

    public function employeeDashboard(Request $request)
    {
        $user = $request->user();

        $recentAttendances = Attendance::where('user_id', $user->id)
            ->with('shift')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'recentAttendances' => $recentAttendances,
        ]);
    }
}
