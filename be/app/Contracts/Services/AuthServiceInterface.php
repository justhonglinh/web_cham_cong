<?php

namespace App\Contracts\Services;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(string $email, string $password): array;
    public function register(array $data): array;
    public function logout(User $user): void;
    public function updateProfile(User $user, array $data): User;
    public function updatePassword(User $user, string $currentPassword, string $newPassword): void;
}
