<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkSummaryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FaceCompareController;

// route call api so sánh khuôn mặt
Route::get('/face-compare', [FaceCompareController::class, 'showForm']);
Route::post('/face-compare', [FaceCompareController::class, 'compare']);

// API so sánh khuôn mặt cho frontend gọi AJAX
Route::post('/api/face-compare', [FaceCompareController::class, 'apiCompare'])->middleware(['auth']);

// giao diện ban đầu
Route::get('/', function () {
    return view('auth.login');
});

// chia luồng sau khi login success
Route::get('/dashboard', [DashboardController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Các route chung cho user đã đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Các route chỉ dành cho manager
Route::middleware(['auth', 'role:manager'])->group(function () {
//    // employees management page
   Route::get('/employees/management', function () {
       $employees = (new UserController())->show();
       return view('employees_management', compact('employees'));
   })->name('employees.management');

    // user CRUD routes
    Route::get('/employees/management', [UserController::class, 'show'])->name('employees.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

    // attendance
    Route::get('/attendance/management', [AttendanceController::class, 'show'])->name('attendance.index');
    Route::put('/attendance/management/{id}', [AttendanceController::class, 'update'])->name('attendance.update');

    // overtime
    Route::get('/overtime/management', [OvertimeController::class, 'show'])->name('overtime.index');
    Route::post('/overtime/management', [OvertimeController::class, 'store'])->name('overtime.store');
    Route::put('overtime/management/{id}', [OvertimeController::class, 'update'])->name('overtime.update');
    Route::delete('/overtime/management/{id}', [OvertimeController::class, 'destroy'])->name('overtime.destroy');

    // shift management
    Route::get('/shift/management', [ShiftController::class, 'show'])->name('shifts.index');
    Route::get('/shift/management/all', [ShiftController::class, 'showAll'])->name('shifts.all');
    Route::post('/shift/management', [ShiftController::class, 'store'])->name('shifts.store');
    Route::put('/shift/management/{id}', [ShiftController::class, 'update'])->name('shifts.update');
    Route::delete('/shift/management/{id}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
    Route::get('/shift/management/search', [ShiftController::class, 'search'])->name('shifts.search');

    // overtime request
    Route::patch('/overtime-requests/{id}/status', [OvertimeRequestController::class, 'updateStatus'])->name('overtimeRequests.update');

    // leave request management
    Route::get('/leave-requests/management', [LeaveRequestController::class, 'index'])->name('leave.index');
    Route::patch('/leave-requests/{id}/status', [LeaveRequestController::class, 'updateStatus'])->name('leave.updateStatus');

    // work summary
    Route::get('/work-summary/management', [WorkSummaryController::class, 'show'])->name('work.index');
    Route::get('/work-summary/export', [WorkSummaryController::class, 'export'])->name('work.export');
    Route::get('/work-summary/search', [WorkSummaryController::class, 'search'])->name('work.search');

    // Location routes
    Route::get('/locations', [App\Http\Controllers\LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/{id}', [App\Http\Controllers\LocationController::class, 'show'])->name('locations.show');
    Route::post('/locations', [App\Http\Controllers\LocationController::class, 'store'])->name('locations.store');
    Route::put('/locations/update', [App\Http\Controllers\LocationController::class, 'updateCurrent'])->name('locations.updateCurrent');
    Route::put('/locations/{id}', [App\Http\Controllers\LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{id}', [App\Http\Controllers\LocationController::class, 'destroy'])->name('locations.destroy');
    Route::patch('/locations/{id}/toggle', [App\Http\Controllers\LocationController::class, 'toggleStatus'])->name('locations.toggle');
    Route::post('/locations/check', [App\Http\Controllers\LocationController::class, 'checkLocation'])->name('locations.check');
});

// Các route chỉ dành cho employee

Route::middleware(['auth', 'role:employee'])->group(function () {
    // Employee dashboard
    Route::get('/employees/dashboard', function () {
        return view('employees.dashboard');
    })->name('employees.dashboard');

    // Route chấm công cho nhân viên (giao diện bật cam chụp ảnh)
    Route::get('/employees/attendance', [AttendanceController::class, 'showAttendanceForm'])->name('employees.attendance');
    Route::post('/employees/attendance', [AttendanceController::class, 'processAttendance'])->name('employees.attendance.process');

    // Face compare route
    Route::get('/employees/face-compare', [FaceCompareController::class, 'showForm'])->name('employees.face-compare');

    //overtime
    Route::get('/overtime/employee', [OvertimeController::class, 'show'])->name('employees.overtime.index');
    Route::post('/overtime/register/{shiftId}', [OvertimeController::class, 'register'])->name('employees.overtime.register');
    Route::delete('/overtime/unregister/{shiftId}', [OvertimeController::class, 'unregister'])->name('employees.overtime.unregister');

    //Lich su cham cong
    Route::get('/employees/attendance/history', [AttendanceController::class, 'history'])->name('employees.attendance.history');

    // Leave request routes
    Route::get('/employees/leave/request', [LeaveRequestController::class, 'showForm'])->name('employees.leave.request');
    Route::post('/employees/leave', [LeaveRequestController::class, 'store'])->name('employees.leave.store');
    Route::get('/employees/leave/history', [LeaveRequestController::class, 'history'])->name('employees.leave.history');
    Route::delete('/employees/leave/{id}', [LeaveRequestController::class, 'destroy'])->name('employees.leave.destroy');
});

require __DIR__.'/auth.php';
