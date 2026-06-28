<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Attendance\AttendanceController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\FaceCompare\FaceCompareController;
use App\Http\Controllers\Api\Leave\LeaveRequestController;
use App\Http\Controllers\Api\Location\LocationController;
use App\Http\Controllers\Api\Overtime\OvertimeController;
use App\Http\Controllers\Api\Shift\ShiftController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\WorkSummary\WorkSummaryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Face compare
    Route::post('/face-compare', [FaceCompareController::class, 'compare']);

    // Auth
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::get('/user',      [AuthController::class, 'user']);
    Route::patch('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/password',  [AuthController::class, 'updatePassword']);

    // Dashboard (cả 2 role đều dùng)
    Route::get('/dashboard',           [DashboardController::class, 'index']);
    Route::get('/employees/dashboard', [DashboardController::class, 'employeeDashboard']);

    /*
    |----------------------------------------------------------------------
    | MANAGER ROUTES
    |----------------------------------------------------------------------
    */

    Route::middleware('role:manager')->group(function () {

        // Nhân viên
        Route::get('/users',         [UserController::class, 'index']);
        Route::post('/users',        [UserController::class, 'store']);
        Route::put('/users/{id}',    [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Chấm công (quản lý)
        Route::get('/attendance/management',      [AttendanceController::class, 'management']);
        Route::put('/attendance/management/{id}', [AttendanceController::class, 'update']);

        // Ca làm việc
        Route::get('/shift/management',         [ShiftController::class, 'index']);
        Route::post('/shift/management',        [ShiftController::class, 'store']);
        Route::put('/shift/management/{id}',    [ShiftController::class, 'update']);
        Route::delete('/shift/management/{id}', [ShiftController::class, 'destroy']);

        // Ca tăng ca (quản lý)
        Route::get('/overtime/management',         [OvertimeController::class, 'management']);
        Route::post('/overtime/management',        [OvertimeController::class, 'store']);
        Route::put('/overtime/management/{id}',    [OvertimeController::class, 'update']);
        Route::delete('/overtime/management/{id}', [OvertimeController::class, 'destroy']);

        // Duyệt yêu cầu tăng ca
        Route::patch('/overtime-requests/{id}/status', [OvertimeController::class, 'updateRequestStatus']);

        // Đơn nghỉ phép (quản lý)
        Route::get('/leave-requests/management',    [LeaveRequestController::class, 'management']);
        Route::patch('/leave-requests/{id}/status', [LeaveRequestController::class, 'updateStatus']);

        // Tổng hợp công
        Route::get('/work-summary/management', [WorkSummaryController::class, 'index']);
        Route::get('/work-summary/export',     [WorkSummaryController::class, 'export']);

        // Địa điểm
        Route::get('/locations',               [LocationController::class, 'index']);
        Route::post('/locations',              [LocationController::class, 'store']);
        Route::put('/locations/{id}',          [LocationController::class, 'update']);
        Route::delete('/locations/{id}',       [LocationController::class, 'destroy']);
        Route::patch('/locations/{id}/toggle', [LocationController::class, 'toggle']);
    });

    /*
    |----------------------------------------------------------------------
    | EMPLOYEE ROUTES
    |----------------------------------------------------------------------
    */

    Route::middleware('role:employee')->group(function () {

        // Chấm công (nhân viên)
        Route::get('/employees/attendance/today',   [AttendanceController::class, 'today']);
        Route::post('/employees/attendance',        [AttendanceController::class, 'processAttendance']);
        Route::get('/employees/attendance/history', [AttendanceController::class, 'history']);

        // Tăng ca (nhân viên)
        Route::get('/overtime/employee',                [OvertimeController::class, 'employeeIndex']);
        Route::post('/overtime/register/{shiftId}',     [OvertimeController::class, 'register']);
        Route::delete('/overtime/unregister/{shiftId}', [OvertimeController::class, 'unregister']);

        // Đơn nghỉ phép (nhân viên)
        Route::post('/employees/leave',        [LeaveRequestController::class, 'store']);
        Route::get('/employees/leave/history', [LeaveRequestController::class, 'history']);
        Route::delete('/employees/leave/{id}', [LeaveRequestController::class, 'destroy']);
    });
});
