<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceInterface;
use App\Exceptions\Api\InvalidCredentialsException;
use App\Exceptions\Api\InvalidResetTokenException;
use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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

    public function register(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'manager',
        ]);

        $token = $user->createToken('nuxt-app')->plainTextToken;

        return ['token' => $token, 'user' => $user];
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

    public function sendResetLink(string $email): void
    {
        Password::sendResetLink(['email' => $email]);
    }

    public function resetPassword(array $data): void
    {
        $status = Password::reset(
            [
                'email'                 => $data['email'],
                'token'                 => $data['token'],
                'password'              => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
            ],
            function (User $user, string $password) {
                $user->update(['password' => Hash::make($password)]);
            }
        );

        if ($status !== PasswordBroker::PASSWORD_RESET) {
            throw new InvalidResetTokenException();
        }
    }
}
