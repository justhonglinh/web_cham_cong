<?php

namespace App\Http\Controllers\Api\Location;

use App\Contracts\Services\LocationServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Location\StoreLocationRequest;
use App\Http\Requests\Api\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LocationController extends Controller
{
    public function __construct(private readonly LocationServiceInterface $locationService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return LocationResource::collection(
            $this->locationService->getAll($request->user()->id)
        );
    }

    public function store(StoreLocationRequest $request): JsonResponse
    {
        $location = $this->locationService->create($request->validated(), $request->user()->id);

        return response()->json([
            'message' => __('messages.location.created'),
            'data'    => new LocationResource($location),
        ], 201);
    }

    public function update(UpdateLocationRequest $request, int $id): JsonResponse
    {
        $location = $this->locationService->update($id, $request->user()->id, $request->validated());

        return response()->json([
            'message' => __('messages.location.updated'),
            'data'    => new LocationResource($location),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->locationService->delete($id, $request->user()->id);

        return response()->json(['message' => __('messages.location.deleted')]);
    }

    public function toggle(Request $request, int $id): JsonResponse
    {
        $location = $this->locationService->toggle($id, $request->user()->id);

        return response()->json([
            'message' => __('messages.location.status_updated'),
            'data'    => new LocationResource($location),
        ]);
    }
}
