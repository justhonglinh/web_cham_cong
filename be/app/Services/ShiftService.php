<?php

namespace App\Services;

use App\Contracts\Services\ShiftServiceInterface;
use App\Exceptions\Api\ShiftInUseException;
use App\Models\Attendance;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Collection;

class ShiftService implements ShiftServiceInterface
{
    public function getAll(int $managerId): Collection
    {
        return Shift::where('user_id', $managerId)
            ->withCount('attendances')
            ->get();
    }

    public function create(array $data, int $managerId): Shift
    {
        return Shift::create([
            'name'       => $data['name'],
            'start_time' => $data['start_time'],
            'end_time'   => $data['end_time'],
            'user_id'    => $managerId,
        ]);
    }

    public function update(int $id, int $managerId, array $data): Shift
    {
        $shift = Shift::where('id', $id)->where('user_id', $managerId)->firstOrFail();

        $shift->update(array_filter([
            'name'       => $data['name'] ?? null,
            'start_time' => $data['start_time'] ?? null,
            'end_time'   => $data['end_time'] ?? null,
        ]));

        return $shift->fresh();
    }

    public function delete(int $id, int $managerId): void
    {
        $shift = Shift::where('id', $id)->where('user_id', $managerId)->firstOrFail();

        $inUse = Attendance::where('shift_id', $shift->id)
            ->where('date', '>=', now()->toDateString())
            ->exists();

        if ($inUse) {
            throw new ShiftInUseException();
        }

        $shift->delete();
    }
}
