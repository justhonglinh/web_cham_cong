<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show()
    {
        $userRole = Auth::user()->role;

        if ($userRole == 'manager') {
            $managerId = Auth::user()->id;

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

            $attendancesCount = Attendance::whereHas('user', function($query) use ($managerId) {
                $query->where('manager', $managerId); // Lọc theo manager
            })
            ->where('status', 'present')
            ->whereDate('created_at', now()->toDateString())
            ->count(); // Đếm số lượng bản ghi


            return view('dashboard_management', compact('employeesCount', 'shiftsCount', 'overtimesCount', 'overtimeRequestsCount','attendancesCount'));
        } else {
            return view('employees.dashboard');
        }
    }
}
