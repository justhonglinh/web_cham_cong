<?php

namespace App\Http\Controllers\Api\User;

use App\Contracts\Services\UserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(private readonly UserServiceInterface $userService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return UserResource::collection(
            $this->userService->getEmployees($request->user()->id)
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated(), $request->user()->id);

        return response()->json([
            'message' => __('messages.user.created'),
            'data'    => new UserResource($user),
        ], 201);
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->update($id, $request->user()->id, $request->validated());

        return response()->json([
            'message' => __('messages.user.updated'),
            'data'    => new UserResource($user),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->userService->delete($id, $request->user()->id);

        return response()->json(['message' => __('messages.user.deleted')]);
    }
}
