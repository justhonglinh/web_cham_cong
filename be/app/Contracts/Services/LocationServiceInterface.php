<?php

namespace App\Contracts\Services;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

interface LocationServiceInterface
{
    public function getAll(int $managerId): Collection;
    public function create(array $data, int $managerId): Location;
    public function update(int $id, int $managerId, array $data): Location;
    public function delete(int $id, int $managerId): void;
    public function toggle(int $id, int $managerId): Location;
}
