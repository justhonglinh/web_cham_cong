<?php

namespace App\Contracts\Services;

use App\Models\OvertimeRequest;
use App\Models\OvertimeShift;
use App\Models\User;

interface OvertimeServiceInterface
{
    public function getManagement(int $managerId): array;
    public function createShift(array $data, int $managerId): OvertimeShift;
    public function updateShift(int $id, int $managerId, array $data): OvertimeShift;
    public function deleteShift(int $id, int $managerId): void;
    public function updateRequestStatus(int $id, string $status): OvertimeRequest;
    public function getEmployeeShifts(User $user): array;
    public function register(int $userId, int $shiftId): OvertimeRequest;
    public function unregister(int $userId, int $shiftId): void;
}
