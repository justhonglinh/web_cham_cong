<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Shift;

class AttendanceController extends Controller
{
    public function show(Request $request)
    {
        $managerName = Auth::user()->name;

        // Lấy danh sách nhân viên dưới quyền manager
        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerName)
            ->pluck('id');

        // Lấy dữ liệu chấm công của các nhân viên trên, có join shift
        $attendances = Attendance::with('shift', 'user')
            ->whereIn('user_id', $employeeIds)
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('attendence_management', compact('attendances'));
    }
}
