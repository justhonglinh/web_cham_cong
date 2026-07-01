<?php

namespace App\Services;

use App\Contracts\Services\OvertimeServiceInterface;
use App\Exceptions\Api\OvertimeAlreadyRegisteredException;
use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OvertimeService implements OvertimeServiceInterface
{
    public function getShifts(int $managerId): Collection
    {
        return OvertimeShift::where('user_id', $managerId)
            ->withCount(['overtimeRequests as registration_count'])
            ->get();
    }

    public function getRequests(int $managerId): Collection
    {
        return OvertimeRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with(['user', 'overtimeShift'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function createShift(array $data, int $managerId): OvertimeShift
    {
        return OvertimeShift::create([
            'user_id'           => $managerId,
            'name'              => $data['name'],
            'start_time'        => $data['start_time'],
            'end_time'          => $data['end_time'],
            'date'              => $data['date'],
            'max_registrations' => $data['max_registrations'] ?? null,
        ]);
    }

    public function updateShift(int $id, int $managerId, array $data): OvertimeShift
    {
        $shift = OvertimeShift::where('id', $id)->where('user_id', $managerId)->firstOrFail();

        $shift->update(array_filter([
            'name'              => $data['name'] ?? null,
            'start_time'        => $data['start_time'] ?? null,
            'end_time'          => $data['end_time'] ?? null,
            'date'              => $data['date'] ?? null,
            'max_registrations' => $data['max_registrations'] ?? null,
        ], fn($v) => !is_null($v)));

        return $shift->fresh();
    }

    public function deleteShift(int $id, int $managerId): void
    {
        OvertimeShift::where('id', $id)->where('user_id', $managerId)->firstOrFail()->delete();
    }

    public function updateRequestStatus(int $id, string $status): OvertimeRequest
    {
        $request         = OvertimeRequest::findOrFail($id);
        $request->status = $status;
        $request->save();

        return $request;
    }

    public function getEmployeeShifts(User $user): Collection
    {
        $shifts = OvertimeShift::where('user_id', $user->manager)
            ->where('date', '>=', now()->toDateString())
            ->withCount(['overtimeRequests as registration_count'])
            ->get();

        $registeredIds = OvertimeRequest::where('user_id', $user->id)
            ->pluck('overtime_shift_id')
            ->toArray();

        foreach ($shifts as $shift) {
            $shift->is_registered = in_array($shift->id, $registeredIds);
        }

        return $shifts;
    }

    public function register(int $userId, int $shiftId): OvertimeRequest
    {
        $exists = OvertimeRequest::where('user_id', $userId)
            ->where('overtime_shift_id', $shiftId)
            ->exists();

        if ($exists) {
            throw new OvertimeAlreadyRegisteredException();
        }

        return OvertimeRequest::create([
            'user_id'           => $userId,
            'overtime_shift_id' => $shiftId,
            'status'            => 'pending',
        ]);
    }

    public function unregister(int $userId, int $shiftId): void
    {
        OvertimeRequest::where('user_id', $userId)
            ->where('overtime_shift_id', $shiftId)
            ->firstOrFail()
            ->delete();
    }
}
