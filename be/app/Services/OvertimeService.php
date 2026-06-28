<?php

namespace App\Services;

use App\Contracts\Services\OvertimeServiceInterface;
use App\Exceptions\Api\OvertimeAlreadyRegisteredException;
use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\User;

class OvertimeService implements OvertimeServiceInterface
{
    public function getManagement(int $managerId): array
    {
        $shifts = OvertimeShift::where('user_id', $managerId)
            ->withCount('overtimeRequests')
            ->get();

        $requests = OvertimeRequest::whereHas('user', fn($q) => $q->where('manager', $managerId))
            ->with(['user', 'overtimeShift'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return ['shifts' => $shifts, 'requests' => $requests];
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

    public function getEmployeeShifts(User $user): array
    {
        $shifts = OvertimeShift::where('user_id', $user->manager)
            ->where('date', '>=', now()->toDateString())
            ->withCount('overtimeRequests')
            ->get();

        $registeredIds = OvertimeRequest::where('user_id', $user->id)
            ->pluck('overtime_shift_id')
            ->toArray();

        return ['shifts' => $shifts, 'registeredIds' => $registeredIds];
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
