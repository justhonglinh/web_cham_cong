<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Contracts\Services\DashboardServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\LeaveRequestResource;
use App\Http\Resources\OvertimeRequestResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardServiceInterface $dashboardService) {}

    public function index(Request $request): JsonResponse
    {
        $data = $this->dashboardService->getManagerDashboard($request->user()->id);

        return response()->json(array_merge($data, [
            'recentOvertimeRequests' => OvertimeRequestResource::collection($data['recentOvertimeRequests']),
            'recentLeaveRequests'    => LeaveRequestResource::collection($data['recentLeaveRequests']),
            'recentAttendances'      => AttendanceResource::collection($data['recentAttendances']),
        ]));
    }

    public function employeeDashboard(Request $request): JsonResponse
    {
        $data = $this->dashboardService->getEmployeeDashboard($request->user());

        return response()->json([
            'recentAttendances' => AttendanceResource::collection($data['recentAttendances']),
        ]);
    }
}
