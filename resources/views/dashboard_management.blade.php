<x-app-layout>
    <x-slot name="header">
    </x-slot>
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-800 -mt-6 -mx-6 px-6 py-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
                    <p class="text-blue-100 text-lg">Tổng quan hệ thống quản lý chấm công</p>
                </div>
                
                <!-- Current Date -->
                <div class="flex items-center space-x-4">
                    <div class="bg-white bg-opacity-20 px-4 py-2 rounded-xl">
                        <p class="text-white text-sm font-medium">{{ now()->format('d/m/Y') }}</p>
                        <p class="text-blue-100 text-xs">
                            @php
                                $days = ['Sunday' => 'Chủ nhật', 'Monday' => 'Thứ 2', 'Tuesday' => 'Thứ 3', 'Wednesday' => 'Thứ 4', 'Thursday' => 'Thứ 5', 'Friday' => 'Thứ 6', 'Saturday' => 'Thứ 7'];
                                echo $days[now()->format('l')] ?? now()->format('l');
                            @endphp
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Tổng nhân viên</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $employeesCount }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-xl">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M3 11h18M5 21h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Ca làm việc</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $shiftsCount }}</p>
                        <p class="text-xs text-gray-500">{{ $activeShifts }} đang sử dụng</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-xl">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Ca tăng ca</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overtimesCount }}</p>
                        <p class="text-xs text-gray-500">{{ $approvedOvertimeRequests }} đã phê duyệt</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-xl">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Chấm công hôm nay</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $attendancesCount }}</p>
                        <p class="text-xs text-gray-500">{{ $lateAttendances }} chấm công muộn</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Pending Requests Alert -->
            @if($pendingOvertimeRequests > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Yêu cầu chờ duyệt
                        </h3>
                        <span class="bg-yellow-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ $pendingOvertimeRequests }} yêu cầu</span>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">Bạn có {{ $pendingOvertimeRequests }} yêu cầu tăng ca đang chờ phê duyệt.</p>
                    <a href="{{ route('overtime.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @endif

            <!-- Late Attendance Alert -->
            @if($lateAttendances > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Chấm công muộn
                        </h3>
                        <span class="bg-red-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ $lateAttendances }} nhân viên</span>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">Hôm nay có {{ $lateAttendances }} nhân viên chấm công muộn hơn 15 phút.</p>
                    <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Charts and Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Weekly Attendance Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Thống kê chấm công tuần này
                        </h3>
                        <div class="flex items-center space-x-4">
                            <div class="bg-white bg-opacity-20 px-3 py-1 rounded-lg">
                                <span class="text-white text-sm font-medium">
                                    @php
                                        $totalWeekAttendance = array_sum(array_column($weeklyStats, 'count'));
                                        $avgDailyAttendance = $totalWeekAttendance > 0 ? round($totalWeekAttendance / count($weeklyStats), 1) : 0;
                                    @endphp
                                    TB: {{ $avgDailyAttendance }}/ngày
                                </span>
                            </div>
                            <div class="bg-white bg-opacity-20 px-3 py-1 rounded-lg">
                                <span class="text-white text-sm font-medium">
                                    Tổng: {{ $totalWeekAttendance }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Chart -->
                    <div class="flex items-end justify-between space-x-2 h-40 mb-6">
                        @php
                            $maxCount = max(array_column($weeklyStats, 'count'));
                            $maxCount = $maxCount > 0 ? $maxCount : 1;
                        @endphp
                        @foreach($weeklyStats as $stat)
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-full bg-gray-200 rounded-t-lg relative group cursor-pointer" style="height: {{ max(20, ($stat['count'] / $maxCount) * 100) }}%">
                                <div class="absolute inset-0 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg transition-all duration-200 group-hover:from-blue-600 group-hover:to-blue-500"></div>
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10">
                                    {{ $stat['count'] }} chấm công
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2 font-medium">{{ $stat['day'] }}</p>
                            <p class="text-xs text-gray-500">{{ $stat['date'] }}</p>
                            <p class="text-xs font-bold text-blue-600">{{ $stat['count'] }}</p>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Weekly Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $highestDay = collect($weeklyStats)->sortByDesc('count')->first();
                            $lowestDay = collect($weeklyStats)->sortBy('count')->first();
                            $workDays = collect($weeklyStats)->where('count', '>', 0)->count();
                        @endphp
                        
                        <!-- Highest Day -->
                        <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-green-800">Cao nhất</p>
                                    <p class="text-lg font-bold text-green-900">{{ $highestDay['day'] ?? 'N/A' }}</p>
                                    <p class="text-xs text-green-600">{{ $highestDay['count'] ?? 0 }} chấm công</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lowest Day -->
                        <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-red-800">Thấp nhất</p>
                                    <p class="text-lg font-bold text-red-900">{{ $lowestDay['day'] ?? 'N/A' }}</p>
                                    <p class="text-xs text-red-600">{{ $lowestDay['count'] ?? 0 }} chấm công</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Work Days -->
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Ngày làm việc</p>
                                    <p class="text-lg font-bold text-blue-900">{{ $workDays }}/7</p>
                                    <p class="text-xs text-blue-600">{{ round(($workDays / 7) * 100, 1) }}% tuần</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Trend Analysis -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Phân tích xu hướng
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            @php
                                $firstHalf = collect($weeklyStats)->take(3)->sum('count');
                                $secondHalf = collect($weeklyStats)->skip(3)->sum('count');
                                $trend = $secondHalf > $firstHalf ? 'tăng' : ($secondHalf < $firstHalf ? 'giảm' : 'ổn định');
                                $trendColor = $secondHalf > $firstHalf ? 'text-green-600' : ($secondHalf < $firstHalf ? 'text-red-600' : 'text-blue-600');
                                $trendIcon = $secondHalf > $firstHalf ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : ($secondHalf < $firstHalf ? 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z');
                            @endphp
                            
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Đầu tuần (T2-T4):</span>
                                <span class="font-semibold">{{ $firstHalf }} chấm công</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Cuối tuần (T5-CN):</span>
                                <span class="font-semibold">{{ $secondHalf }} chấm công</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Xu hướng:</span>
                                <span class="font-semibold {{ $trendColor }} flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $trendIcon }}"></path>
                                    </svg>
                                    {{ $trend }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Hiệu suất:</span>
                                <span class="font-semibold {{ $totalWeekAttendance >= ($employeesCount * 5) ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $totalWeekAttendance >= ($employeesCount * 5) ? 'Tốt' : 'Cần cải thiện' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Thống kê nhanh
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Tổng yêu cầu OT</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">{{ $overtimeRequestsCount }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Đã phê duyệt</span>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ $approvedOvertimeRequests }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Chờ duyệt</span>
                        </div>
                        <span class="text-lg font-bold text-yellow-600">{{ $pendingOvertimeRequests }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Ca đang sử dụng</span>
                        </div>
                        <span class="text-lg font-bold text-purple-600">{{ $activeShifts }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Overtime Requests -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Yêu cầu tăng ca gần đây
                        </h3>
                        <a href="{{ route('overtime.index') }}" class="text-pink-100 hover:text-white text-sm">Xem tất cả</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentOvertimeRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOvertimeRequests as $request)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-rose-500 rounded-full flex items-center justify-center">
                                    @if($request->user && $request->user->avatar)
                                        <img src="{{ asset('storage/' . $request->user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <svg class="w-5 h-5 text-white" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $request->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->overtimeShift->name ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($request->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->format('d/m H:i') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">Chưa có yêu cầu tăng ca nào</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Attendances -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Chấm công gần đây
                        </h3>
                        <a href="{{ route('attendance.index') }}" class="text-indigo-100 hover:text-white text-sm">Xem tất cả</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentAttendances->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentAttendances as $attendance)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                                    @if($attendance->user && $attendance->user->avatar)
                                        <img src="{{ asset('storage/' . $attendance->user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <svg class="w-5 h-5 text-white" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $attendance->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $attendance->shift->name ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 
                                           ($attendance->status === 'absent' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $attendance->date }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">Chưa có bản ghi chấm công nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
