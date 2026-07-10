<?php

namespace App\Services;

use App\Contracts\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function getEmployees(int $managerId, ?string $search = null): LengthAwarePaginator
    {
        return User::where('role', 'employee')
            ->where('manager', $managerId)
            ->with('details')
            ->when($search, fn($q) => $q->where(fn($q2) => $q2
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            ))
            ->paginate(20);
    }

    public function create(array $data, int $managerId): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'employee',
            'manager'  => $managerId,
        ]);
    }

    public function update(int $id, int $managerId, array $data): User
    {
        $user = User::where('id', $id)->where('manager', $managerId)->firstOrFail();

        $payload = array_filter([
            'name'  => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return $user->fresh();
    }

    public function delete(int $id, int $managerId): void
    {
        User::where('id', $id)->where('manager', $managerId)->firstOrFail()->delete();
    }
}
