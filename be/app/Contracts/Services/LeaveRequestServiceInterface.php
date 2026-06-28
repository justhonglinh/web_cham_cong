<?php

namespace App\Contracts\Services;

use App\Models\LeaveRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeaveRequestServiceInterface
{
    public function getManagement(int $managerId): LengthAwarePaginator;
    public function updateStatus(int $id, string $status, int $approverId): LeaveRequest;
    public function create(array $data, int $userId): LeaveRequest;
    public function history(int $userId): LengthAwarePaginator;
    public function delete(int $id, int $userId): void;
}
