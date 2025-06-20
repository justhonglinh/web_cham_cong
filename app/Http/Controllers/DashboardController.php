<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function show()
    {
        $userRole = Auth::user()->role;

        if ($userRole == 'manager') {
            $managerId = Auth::user()->id;
            $today = now()->toDateString();

            // Lấy danh sách nhân viên do quản lý này quản lý
            $employeesCount = User::where('manager', $managerId)->count();

            // Lấy số lượng ca làm việc của quản lý này
            $shiftsCount = Shift::where('user_id', $managerId)->count();

            // Lấy số lượng yêu cầu tăng ca của quản lý này
            $overtimesCount = OvertimeShift::where('user_id', $managerId)->count();

            // Lấy số lượng yêu cầu tăng ca có user thuộc quản lý này
            $overtimeRequestsCount = OvertimeRequest::whereHas('user', function($query) use ($managerId) {
                $query->where('manager', $managerId);
            })->count();

            // Lấy số lượng chấm công hôm nay
            $attendancesCount = Attendance::whereHas('user', function($query) use ($managerId) {
                $query->where('manager', $managerId);
            })
            ->where('status', 'present')
            ->whereDate('created_at', $today)
            ->count();

            // Lấy số yêu cầu tăng ca đang chờ duyệt
            $pendingOvertimeRequests = OvertimeRequest::whereHas('user', function($query) use ($managerId) {
                $query->where('manager', $managerId);
            })
            ->where('status', 'pending')
            ->count();

            // Lấy số nhân viên chấm công muộn hôm nay (sau 8:15 AM)
            $lateAttendances = Attendance::whereHas('user', function($query) use ($managerId) {
                $query->where('manager', $managerId);
            })
            ->whereDate('date', $today)
            ->whereNotNull('check_in_time')
            ->where('check_in_time', '>', '08:15:00')
            ->count();

            // Lấy số ca làm việc đang được sử dụng
            $activeShifts = Shift::where('user_id', $managerId)
                ->whereHas('attendances')
                ->count();

            // Lấy số yêu cầu tăng ca đã được phê duyệt
            $approvedOvertimeRequests = OvertimeRequest::whereHas('user', function($query) use ($managerId) {
                $query->where('manager', $managerId);
            })
            ->where('status', 'approved')
            ->count();

            // Lấy các yêu cầu tăng ca gần đây
            $recentOvertimeRequests = OvertimeRequest::whereHas('user', function($query) use ($managerId) {
                $query->where('manager', $managerId);
            })
            ->with('user', 'overtimeShift')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

            // Lấy các chấm công gần đây
            $recentAttendances = Attendance::whereHas('user', function($query) use ($managerId) {
                $query->where('manager', $managerId);
            })
            ->with('user', 'shift')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

            // Thống kê theo ngày trong tuần
            $weeklyStats = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->toDateString();
                $dailyAttendances = Attendance::whereHas('user', function($query) use ($managerId) {
                    $query->where('manager', $managerId);
                })
                ->whereDate('date', $date)
                ->where('status', 'present')
                ->count();
                
                $weeklyStats[] = [
                    'date' => Carbon::parse($date)->format('d/m'),
                    'day' => Carbon::parse($date)->format('D'),
                    'count' => $dailyAttendances
                ];
            }

            return view('dashboard_management', compact(
                'employeesCount', 
                'shiftsCount', 
                'overtimesCount', 
                'overtimeRequestsCount',
                'attendancesCount',
                'pendingOvertimeRequests',
                'lateAttendances',
                'activeShifts',
                'approvedOvertimeRequests',
                'recentOvertimeRequests',
                'recentAttendances',
                'weeklyStats'
            ));
        } else {
            return view('employees.dashboard');
        }
    }
}
