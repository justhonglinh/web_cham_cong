<?php

namespace App\Contracts\Services;

use App\Models\Attendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AttendanceServiceInterface
{
    public function getManagement(int $managerId, int $month, int $year): LengthAwarePaginator;
    public function update(int $id, array $data): Attendance;
    public function today(int $userId): ?Attendance;
    public function history(int $userId): LengthAwarePaginator;
    public function processCheckIn(int $userId): Attendance;
}
