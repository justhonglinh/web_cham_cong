<?php

namespace App\Services;

use App\Contracts\Services\LocationServiceInterface;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

class LocationService implements LocationServiceInterface
{
    public function getAll(int $managerId): Collection
    {
        return Location::where('user_id', $managerId)->get();
    }

    public function create(array $data, int $managerId): Location
    {
        return Location::create(array_merge($data, ['user_id' => $managerId]));
    }

    public function update(int $id, int $managerId, array $data): Location
    {
        $location = Location::where('id', $id)->where('user_id', $managerId)->firstOrFail();
        $location->update($data);

        return $location->fresh();
    }

    public function delete(int $id, int $managerId): void
    {
        Location::where('id', $id)->where('user_id', $managerId)->firstOrFail()->delete();
    }

    public function toggle(int $id, int $managerId): Location
    {
        $location            = Location::where('id', $id)->where('user_id', $managerId)->firstOrFail();
        $location->is_active = !$location->is_active;
        $location->save();

        return $location;
    }
}
