<?php

namespace App\Providers;

use App\Contracts\Services\AttendanceServiceInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Services\DashboardServiceInterface;
use App\Contracts\Services\LeaveRequestServiceInterface;
use App\Contracts\Services\LocationServiceInterface;
use App\Contracts\Services\OvertimeServiceInterface;
use App\Contracts\Services\ShiftServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Services\AttendanceService;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\LeaveRequestService;
use App\Services\LocationService;
use App\Services\OvertimeService;
use App\Services\ShiftService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(ShiftServiceInterface::class, ShiftService::class);
        $this->app->bind(AttendanceServiceInterface::class, AttendanceService::class);
        $this->app->bind(OvertimeServiceInterface::class, OvertimeService::class);
        $this->app->bind(LeaveRequestServiceInterface::class, LeaveRequestService::class);
        $this->app->bind(LocationServiceInterface::class, LocationService::class);
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
    }

    public function boot(): void
    {
        //
    }
}
