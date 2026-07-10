<?php

namespace App\Http\Controllers\Api\Overtime;

use App\Contracts\Services\OvertimeServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Overtime\StoreOvertimeShiftRequest;
use App\Http\Requests\Api\Overtime\UpdateOvertimeRequestStatusRequest;
use App\Http\Requests\Api\Overtime\UpdateOvertimeShiftRequest;
use App\Http\Resources\OvertimeRequestResource;
use App\Http\Resources\OvertimeShiftResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OvertimeController extends Controller
{
    public function __construct(private readonly OvertimeServiceInterface $overtimeService) {}

    public function management(Request $request): JsonResponse
    {
        $shifts = $this->overtimeService->getShifts($request->user()->id);

        return response()->json([
            'data' => OvertimeShiftResource::collection($shifts),
        ]);
    }

    public function managementRequests(Request $request): AnonymousResourceCollection
    {
        return OvertimeRequestResource::collection(
            $this->overtimeService->getRequests($request->user()->id)
        );
    }

    public function store(StoreOvertimeShiftRequest $request): JsonResponse
    {
        $shift = $this->overtimeService->createShift($request->validated(), $request->user()->id);

        return response()->json([
            'message' => __('messages.overtime.shift_created'),
            'data'    => new OvertimeShiftResource($shift),
        ], 201);
    }

    public function update(UpdateOvertimeShiftRequest $request, int $id): JsonResponse
    {
        $shift = $this->overtimeService->updateShift($id, $request->user()->id, $request->validated());

        return response()->json([
            'message' => __('messages.overtime.shift_updated'),
            'data'    => new OvertimeShiftResource($shift),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->overtimeService->deleteShift($id, $request->user()->id);

        return response()->json(['message' => __('messages.overtime.shift_deleted')]);
    }

    public function updateRequestStatus(UpdateOvertimeRequestStatusRequest $request, int $id): JsonResponse
    {
        $overtimeRequest = $this->overtimeService->updateRequestStatus($id, $request->status);

        return response()->json([
            'message' => __('messages.overtime.request_updated'),
            'data'    => new OvertimeRequestResource($overtimeRequest),
        ]);
    }

    public function employeeIndex(Request $request): JsonResponse
    {
        $shifts = $this->overtimeService->getEmployeeShifts($request->user());

        return response()->json([
            'data' => OvertimeShiftResource::collection($shifts),
        ]);
    }

    public function register(Request $request, int $shiftId): JsonResponse
    {
        $this->overtimeService->register($request->user()->id, $shiftId);

        return response()->json(['message' => __('messages.overtime.registered')], 201);
    }

    public function unregister(Request $request, int $shiftId): JsonResponse
    {
        $this->overtimeService->unregister($request->user()->id, $shiftId);

        return response()->json(['message' => __('messages.overtime.unregistered')]);
    }
}
