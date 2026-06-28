<?php

namespace App\Services;

use App\Contracts\Services\DashboardServiceInterface;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;

class DashboardService implements DashboardServiceInterface
{
    public function getManagerDashboard(int $managerId): array
    {
        $today     = now()->toDateString();
        $weekStart = Carbon::now()->subDays(6)->toDateString();

        $employeesCount = User::where('manager', $managerId)->count();

        $allShifts = Shift::where('user_id', $managerId)
            ->withCount('attendances')
            ->withCount(['attendances as past_count' => fn($q) => $q->where('date', '<', $today)])
            ->get();

        $shiftsCount  = $allShifts->count();
        $oldShifts    = $allShifts->where('past_count', '>', 0)->count();
        $unusedShifts = $allShifts->where('attendances_count', 0)->count();
        $activeShifts = $shiftsCount - $oldShifts;

        $overtimesCount           = OvertimeShift::where('user_id', $managerId)->count();
        $overtimeRequestsCount    = OvertimeRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))->count();
        $pendingOvertimeRequests  = OvertimeRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))->where('status', 'pending')->count();
        $approvedOvertimeRequests = OvertimeRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))->where('status', 'approved')->count();

        $attendancesCount = Attendance::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->where('status', 'present')->whereDate('date', $today)->count();

        $lateAttendances = Attendance::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->whereDate('date', $today)->whereNotNull('check_in_time')
            ->where('check_in_time', '>', '08:15:00')->count();

        $pendingLeaveRequests  = LeaveRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))->where('status', 'pending')->count();
        $approvedLeaveRequests = LeaveRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))->where('status', 'approved')->count();

        $rawStats = Attendance::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->where('status', 'present')->whereBetween('date', [$weekStart, $today])
            ->selectRaw('date, COUNT(*) as count')->groupBy('date')
            ->pluck('count', 'date');

        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = Carbon::now()->subDays($i)->toDateString();
            $weeklyStats[] = [
                'date'  => Carbon::parse($date)->format('d/m'),
                'day'   => Carbon::parse($date)->format('D'),
                'count' => $rawStats[$date] ?? 0,
            ];
        }

        $shiftTimeStats = $allShifts->map(fn($shift) => [
            'name'        => $shift->name,
            'start_time'  => $shift->start_time,
            'end_time'    => $shift->end_time,
            'usage_count' => $shift->attendances_count,
            'is_active'   => $shift->past_count === 0,
        ])->values()->toArray();

        $recentOvertimeRequests = OvertimeRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with(['user', 'overtimeShift'])->orderByDesc('created_at')->limit(5)->get();

        $recentLeaveRequests = LeaveRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with('user')->orderByDesc('created_at')->limit(5)->get();

        $recentAttendances = Attendance::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with(['user', 'shift'])->orderByDesc('created_at')->limit(5)->get();

        return compact(
            'employeesCount', 'shiftsCount', 'activeShifts', 'oldShifts', 'unusedShifts',
            'overtimesCount', 'approvedOvertimeRequests', 'pendingOvertimeRequests',
            'attendancesCount', 'lateAttendances', 'pendingLeaveRequests', 'approvedLeaveRequests',
            'overtimeRequestsCount', 'weeklyStats', 'shiftTimeStats',
            'recentOvertimeRequests', 'recentAttendances', 'recentLeaveRequests'
        );
    }

    public function getEmployeeDashboard(User $user): array
    {
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->with('shift')->orderByDesc('date')->limit(5)->get();

        return ['recentAttendances' => $recentAttendances];
    }
}
