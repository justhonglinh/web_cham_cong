<?php

namespace App\Http\Controllers\Api\Shift;

use App\Contracts\Services\ShiftServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shift\StoreShiftRequest;
use App\Http\Requests\Api\Shift\UpdateShiftRequest;
use App\Http\Resources\ShiftResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShiftController extends Controller
{
    public function __construct(private readonly ShiftServiceInterface $shiftService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return ShiftResource::collection(
            $this->shiftService->getAll($request->user()->id)
        );
    }

    public function store(StoreShiftRequest $request): JsonResponse
    {
        $shift = $this->shiftService->create($request->validated(), $request->user()->id);

        return response()->json([
            'message' => __('messages.shift.created'),
            'data'    => new ShiftResource($shift),
        ], 201);
    }

    public function update(UpdateShiftRequest $request, int $id): JsonResponse
    {
        $shift = $this->shiftService->update($id, $request->user()->id, $request->validated());

        return response()->json([
            'message' => __('messages.shift.updated'),
            'data'    => new ShiftResource($shift),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->shiftService->delete($id, $request->user()->id);

        return response()->json(['message' => __('messages.shift.deleted')]);
    }
}
