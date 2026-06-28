<?php

namespace App\Contracts\Services;

use App\Models\User;

interface DashboardServiceInterface
{
    public function getManagerDashboard(int $managerId): array;
    public function getEmployeeDashboard(User $user): array;
}
