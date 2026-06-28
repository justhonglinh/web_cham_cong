<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiAttendanceController;
use App\Http\Controllers\Api\ApiShiftController;
use App\Http\Controllers\Api\ApiOvertimeController;
use App\Http\Controllers\Api\ApiLeaveRequestController;
use App\Http\Controllers\Api\ApiWorkSummaryController;
use App\Http\Controllers\Api\ApiLocationController;
use Illuminate\Support\Facades\Route;

// Auth routes (public)
Route::post('/login', [ApiAuthController::class, 'login']);

// Authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::patch('/profile', [ApiAuthController::class, 'updateProfile']);
    Route::put('/password', [ApiAuthController::class, 'updatePassword']);

    // Dashboard
    Route::get('/dashboard', [ApiDashboardController::class, 'index']);
    Route::get('/employees/dashboard', [ApiDashboardController::class, 'employeeDashboard']);

    // Manager routes
    Route::middleware('role:manager')->group(function () {
        // Users
        Route::get('/users', [ApiUserController::class, 'index']);
        Route::post('/users', [ApiUserController::class, 'store']);
        Route::put('/users/{id}', [ApiUserController::class, 'update']);
        Route::delete('/users/{id}', [ApiUserController::class, 'destroy']);

        // Attendance management
        Route::get('/attendance/management', [ApiAttendanceController::class, 'management']);
        Route::put('/attendance/management/{id}', [ApiAttendanceController::class, 'update']);

        // Shift management
        Route::get('/shift/management', [ApiShiftController::class, 'index']);
        Route::post('/shift/management', [ApiShiftController::class, 'store']);
        Route::put('/shift/management/{id}', [ApiShiftController::class, 'update']);
        Route::delete('/shift/management/{id}', [ApiShiftController::class, 'destroy']);

        // Overtime management (manager)
        Route::get('/overtime/management', [ApiOvertimeController::class, 'management']);
        Route::post('/overtime/management', [ApiOvertimeController::class, 'store']);
        Route::put('/overtime/management/{id}', [ApiOvertimeController::class, 'update']);
        Route::delete('/overtime/management/{id}', [ApiOvertimeController::class, 'destroy']);

        // Overtime requests (approve/reject)
        Route::patch('/overtime-requests/{id}/status', [ApiOvertimeController::class, 'updateRequestStatus']);

        // Leave requests management
        Route::get('/leave-requests/management', [ApiLeaveRequestController::class, 'management']);
        Route::patch('/leave-requests/{id}/status', [ApiLeaveRequestController::class, 'updateStatus']);

        // Work summary
        Route::get('/work-summary/management', [ApiWorkSummaryController::class, 'index']);
        Route::get('/work-summary/export', [ApiWorkSummaryController::class, 'export']);

        // Locations
        Route::get('/locations', [ApiLocationController::class, 'index']);
        Route::post('/locations', [ApiLocationController::class, 'store']);
        Route::put('/locations/{id}', [ApiLocationController::class, 'update']);
        Route::delete('/locations/{id}', [ApiLocationController::class, 'destroy']);
        Route::patch('/locations/{id}/toggle', [ApiLocationController::class, 'toggle']);
    });

    // Employee routes
    Route::middleware('role:employee')->group(function () {
        // Attendance
        Route::get('/employees/attendance/today', [ApiAttendanceController::class, 'today']);
        Route::post('/employees/attendance', [ApiAttendanceController::class, 'processAttendance']);
        Route::get('/employees/attendance/history', [ApiAttendanceController::class, 'history']);

        // Overtime
        Route::get('/overtime/employee', [ApiOvertimeController::class, 'employeeIndex']);
        Route::post('/overtime/register/{shiftId}', [ApiOvertimeController::class, 'register']);
        Route::delete('/overtime/unregister/{shiftId}', [ApiOvertimeController::class, 'unregister']);

        // Leave requests
        Route::post('/employees/leave', [ApiLeaveRequestController::class, 'store']);
        Route::get('/employees/leave/history', [ApiLeaveRequestController::class, 'history']);
        Route::delete('/employees/leave/{id}', [ApiLeaveRequestController::class, 'destroy']);
    });
});
