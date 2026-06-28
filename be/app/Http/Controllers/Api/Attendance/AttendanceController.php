<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Contracts\Services\AttendanceServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Attendance\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceServiceInterface $attendanceService) {}

    public function management(Request $request): AnonymousResourceCollection
    {
        $month = (int) $request->input('month', now()->month);
        $year  = (int) $request->input('year', now()->year);

        return AttendanceResource::collection(
            $this->attendanceService->getManagement($request->user()->id, $month, $year)
        );
    }

    public function update(UpdateAttendanceRequest $request, int $id): JsonResponse
    {
        $attendance = $this->attendanceService->update($id, $request->validated());

        return response()->json([
            'message' => __('messages.attendance.updated'),
            'data'    => new AttendanceResource($attendance),
        ]);
    }

    public function today(Request $request): JsonResponse
    {
        $attendance = $this->attendanceService->today($request->user()->id);

        return response()->json([
            'data' => $attendance ? new AttendanceResource($attendance) : null,
        ]);
    }

    public function history(Request $request): AnonymousResourceCollection
    {
        return AttendanceResource::collection(
            $this->attendanceService->history($request->user()->id)
        );
    }

    public function processAttendance(Request $request): JsonResponse
    {
        $attendance = $this->attendanceService->processCheckIn($request->user()->id);

        return response()->json([
            'message' => __('messages.attendance.checked'),
            'data'    => new AttendanceResource($attendance),
        ]);
    }
}
