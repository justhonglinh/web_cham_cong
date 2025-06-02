<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FaceCompareController;

// route call api so sánh khuôn mặt
Route::get('/face-compare', [FaceCompareController::class, 'showForm']);
Route::post('/face-compare', [FaceCompareController::class, 'compare']);

// giao diện ban đầu
Route::get('/', function () {
    return view('auth.login');
});

// chia luồng sau khi login success
Route::get('/dashboard', function () {
    $userRole = Auth::user()->role;

    if ($userRole == 'manager') {
        $employees = (new UserController())->show();
        return view('dashboard', compact('employees'));
    } else {
        return view('employees.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Các route chung cho user đã đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Các route chỉ dành cho manager
Route::middleware(['auth', 'role:manager'])->group(function () {
    // employees management page
    Route::get('/employees/management', function () {
        $employees = (new UserController())->show();
        return view('employees_management', compact('employees'));
    })->name('employees.management');

    // user CRUD routes
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // attendance
    Route::get('/attendance/management', [AttendanceController::class, 'show'])->name('attendance.index');
    Route::patch('/attendance/management/{id}', [AttendanceController::class, 'update'])->name('attendance.update');

    // overtime
    Route::get('/overtime/management', [OvertimeController::class, 'show'])->name('overtime.index');
    Route::post('/overtime/management', [OvertimeController::class, 'store'])->name('overtime.store');
    Route::put('overtime/management/{id}', [OvertimeController::class, 'update'])->name('overtime.update');
    Route::delete('/overtime/management/{id}', [OvertimeController::class, 'destroy'])->name('overtime.destroy');

    // shift management
    Route::get('/shift/management', [ShiftController::class, 'show'])->name('shifts.index');
    Route::post('/shift/management', [ShiftController::class, 'store'])->name('shifts.store');
    Route::put('/shift/management/{id}', [ShiftController::class, 'update'])->name('shifts.update');
    Route::delete('/shift/management/{id}', [ShiftController::class, 'destroy'])->name('shifts.destroy');

    // overtime request
    Route::patch('/overtime-requests/{id}/status', [OvertimeRequestController::class, 'updateStatus'])->name('overtimeRequests.update');
});

// Các route chỉ dành cho employee

Route::middleware(['auth', 'role:employee'])->group(function () {

});
require __DIR__.'/auth.php';
