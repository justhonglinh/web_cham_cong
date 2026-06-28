<?php

namespace App\Contracts\Services;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Collection;

interface ShiftServiceInterface
{
    public function getAll(int $managerId): Collection;
    public function create(array $data, int $managerId): Shift;
    public function update(int $id, int $managerId, array $data): Shift;
    public function delete(int $id, int $managerId): void;
}
