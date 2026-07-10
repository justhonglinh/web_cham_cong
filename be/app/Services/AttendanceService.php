<?php

namespace App\Services;

use App\Contracts\Services\AttendanceServiceInterface;
use App\Exceptions\Api\AttendanceAlreadyDoneException;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttendanceService implements AttendanceServiceInterface
{
    public function getManagement(int $managerId, int $month, int $year): LengthAwarePaginator
    {
        $employeeIds = User::where('manager', $managerId)->pluck('id');

        return Attendance::with(['user', 'shift'])
            ->whereIn('user_id', $employeeIds)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderByDesc('date')
            ->paginate(20);
    }

    public function update(int $id, array $data): Attendance
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->fill([
            'check_in_time'  => $data['check_in'] ?? null,
            'check_out_time' => $data['check_out'] ?? null,
            'status'         => $data['status'] ?? $attendance->status,
        ]);

        $attendance->save();

        return $attendance;
    }

    public function today(int $userId): ?Attendance
    {
        return Attendance::with('shift')
            ->where('user_id', $userId)
            ->where('date', now()->toDateString())
            ->first();
    }

    public function history(int $userId): LengthAwarePaginator
    {
        return Attendance::with('shift')
            ->where('user_id', $userId)
            ->orderByDesc('date')
            ->paginate(20);
    }

    public function processCheckIn(int $userId): Attendance
    {
        $attendance = Attendance::firstOrNew([
            'user_id' => $userId,
            'date'    => now()->toDateString(),
        ]);

        if (!$attendance->check_in_time) {
            $attendance->check_in_time = now()->format('H:i:s');
            $attendance->status        = 'present';
        } elseif (!$attendance->check_out_time) {
            $attendance->check_out_time = now()->format('H:i:s');
        } else {
            throw new AttendanceAlreadyDoneException();
        }

        $attendance->save();

        return $attendance->load('shift');
    }
}
