<?php

namespace App\Contracts\Services;

use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface OvertimeServiceInterface
{
    public function getShifts(int $managerId): Collection;
    public function getRequests(int $managerId): LengthAwarePaginator;
    public function createShift(array $data, int $managerId): OvertimeShift;
    public function updateShift(int $id, int $managerId, array $data): OvertimeShift;
    public function deleteShift(int $id, int $managerId): void;
    public function updateRequestStatus(int $id, string $status): OvertimeRequest;
    public function getEmployeeShifts(User $user): Collection;
    public function register(int $userId, int $shiftId): OvertimeRequest;
    public function unregister(int $userId, int $shiftId): void;
}
