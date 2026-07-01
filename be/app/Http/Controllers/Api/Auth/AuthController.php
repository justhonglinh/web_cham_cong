<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\UpdatePasswordRequest;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->email, $request->password);

        return response()->json([
            'token' => $result['token'],
            'user'  => new UserResource($result['user']),
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'token' => $result['token'],
            'user'  => new UserResource($result['user']),
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => __('messages.auth.logout_success')]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()->load('details')),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->authService->updateProfile($request->user(), $request->validated());

        return response()->json([
            'message' => __('messages.auth.profile_updated'),
            'user'    => new UserResource($user),
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $this->authService->updatePassword(
            $request->user(),
            $request->current_password,
            $request->password
        );

        return response()->json(['message' => __('messages.auth.password_updated')]);
    }
}
