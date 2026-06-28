<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceInterface;
use App\Exceptions\Api\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        $token = $user->createToken('nuxt-app')->plainTextToken;

        return ['token' => $token, 'user' => $user->load('details')];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update(['name' => $data['name']]);

        return $user->fresh('details');
    }

    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'current_password' => [__('messages.auth.password_incorrect')],
            ]);
        }

        $user->update(['password' => Hash::make($newPassword)]);
    }
}
