<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getEmployees(int $managerId): LengthAwarePaginator;
    public function create(array $data, int $managerId): User;
    public function update(int $id, int $managerId, array $data): User;
    public function delete(int $id, int $managerId): void;
}
