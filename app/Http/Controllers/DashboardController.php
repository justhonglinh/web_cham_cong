<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show()
    {
        $userRole = Auth::user()->role;

        if ($userRole == 'manager') {
            // Giả sử UserController::show() trả về danh sách nhân viên,
            // bạn nên gọi service hoặc model ở đây trực tiếp.
            $employees = app(\App\Http\Controllers\UserController::class)->show();

            return view('dashboard_management', compact('employees'));
        } else {
            return view('employees.dashboard');
        }
    }
}
