<?php

namespace App\Http\Controllers\Api\Leave;

use App\Contracts\Services\LeaveRequestServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Leave\StoreLeaveRequestRequest;
use App\Http\Requests\Api\Leave\UpdateLeaveStatusRequest;
use App\Http\Resources\LeaveRequestResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeaveRequestController extends Controller
{
    public function __construct(private readonly LeaveRequestServiceInterface $leaveRequestService) {}

    public function management(Request $request): AnonymousResourceCollection
    {
        return LeaveRequestResource::collection(
            $this->leaveRequestService->getManagement($request->user()->id)
        );
    }

    public function updateStatus(UpdateLeaveStatusRequest $request, int $id): JsonResponse
    {
        $leave = $this->leaveRequestService->updateStatus($id, $request->status, $request->user()->id);

        return response()->json([
            'message' => __('messages.leave.status_updated'),
            'data'    => new LeaveRequestResource($leave),
        ]);
    }

    public function store(StoreLeaveRequestRequest $request): JsonResponse
    {
        $leave = $this->leaveRequestService->create($request->validated(), $request->user()->id);

        return response()->json([
            'message' => __('messages.leave.created'),
            'data'    => new LeaveRequestResource($leave),
        ], 201);
    }

    public function history(Request $request): AnonymousResourceCollection
    {
        return LeaveRequestResource::collection(
            $this->leaveRequestService->history($request->user()->id)
        );
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->leaveRequestService->delete($id, $request->user()->id);

        return response()->json(['message' => __('messages.leave.deleted')]);
    }
}
