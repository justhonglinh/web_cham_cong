<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function show()
    {
        // lấy tên quản lý
        $managerName = Auth::user()->name;

        // lấy tên nhân viên dưới quản lý
        $employeeIds = User::where('role', 'employee')
            ->where('manager', $managerName)
            ->pluck('id');

        $overtimeRequests = OvertimeRequest::with('user', 'overtimeShift')
            ->whereIn('user_id', $employeeIds)
            ->orderBy('created_at', 'asc')   // theo thứ tự từ cũ nhất đến mới nhất
            ->paginate(10);

        return view('overtime_management', compact('overtimeRequests'));
    }

}
