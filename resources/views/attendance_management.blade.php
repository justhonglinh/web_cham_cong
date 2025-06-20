<x-app-layout>
    <x-slot name="header">
        <x-attendance-modal :shifts="$shifts" />
        <x-attendance-overtime-modal :shifts="$overtimes" />
    </x-slot>
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 -mt-6 -mx-6 px-6 py-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Quản lý chấm công</h1>
                    <p class="text-blue-100 text-lg">Theo dõi và quản lý chấm công nhân viên</p>
                </div>
                
                <!-- View Mode Toggle -->
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-1">
                        <div class="flex">
                            <button 
                                @click="viewMode = 'overview'" 
                                :class="viewMode === 'overview' ? 'bg-white text-blue-600 shadow-lg' : 'text-white hover:bg-white/20'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Tổng quan</span>
                            </button>
                            <button 
                                @click="viewMode = 'detailed'" 
                                :class="viewMode === 'detailed' ? 'bg-white text-blue-600 shadow-lg' : 'text-white hover:bg-white/20'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                <span>Chi tiết</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text" id="search"
                            class="pl-10 pr-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg focus:ring-2 focus:ring-white/50 focus:outline-none text-white placeholder-blue-100 w-64"
                            placeholder="Tìm kiếm nhân viên...">
                        <span class="absolute left-3 top-2.5 text-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div x-data="{ viewMode: 'overview', activeTab: 'attendance' }" class="max-w-7xl mx-auto">
        <!-- Overview Mode -->
        <div x-show="viewMode === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-4">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tổng chấm công</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendance_shifts->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-xl">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tổng tăng ca</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendance_overtimes->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-xl">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Hoàn thành</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendance_shifts->where('status', 'completed')->count() + $attendance_overtimes->where('status', 'completed')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-xl">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Đang làm</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendance_shifts->where('status', 'in_progress')->count() + $attendance_overtimes->where('status', 'in_progress')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Attendance -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Chấm công gần đây
                            </h3>
                            <span class="bg-blue-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ $attendance_shifts->count() }} records</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($attendance_shifts->take(5) as $shift)
                                @if(is_object($shift) && isset($shift->user))
                                <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <img src="/storage/{{ $shift->user->avatar }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                    <div class="ml-4 flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $shift->user->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $shift->shift->name ?? 'N/A' }} • {{ $shift->start_time }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $shift->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($shift->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $shift->status === 'completed' ? 'Hoàn thành' : 
                                               ($shift->status === 'in_progress' ? 'Đang làm' : 'Chưa bắt đầu') }}
                                        </span>
                                        <button data-user='@json($shift)' class="openDetailModal p-1 text-gray-400 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <button @click="viewMode = 'detailed'; activeTab = 'attendance'" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                Xem tất cả chấm công →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Overtime -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Tăng ca gần đây
                            </h3>
                            <span class="bg-purple-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ $attendance_overtimes->count() }} records</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($attendance_overtimes->take(5) as $shift)
                                @if(is_object($shift) && isset($shift->user))
                                <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <img src="/storage/{{ $shift->user->avatar }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                    <div class="ml-4 flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $shift->user->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $shift->overtimeShift->name ?? 'N/A' }} • {{ $shift->start_time }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $shift->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($shift->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $shift->status === 'completed' ? 'Hoàn thành' : 
                                               ($shift->status === 'in_progress' ? 'Đang làm' : 'Chưa bắt đầu') }}
                                        </span>
                                        <button data-attendance='@json($shift)' class="openDetailOvertimeModal p-1 text-gray-400 hover:text-purple-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <button @click="viewMode = 'detailed'; activeTab = 'overtime'" class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                                Xem tất cả tăng ca →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Mode -->
        <div x-show="viewMode === 'detailed'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-4">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button 
                            @click="activeTab = 'attendance'" 
                            :class="activeTab === 'attendance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Chấm Công</span>
                            <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs font-medium">{{ $attendance_shifts->total() }}</span>
                        </button>
                        <button 
                            @click="activeTab = 'overtime'" 
                            :class="activeTab === 'overtime' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Tăng Ca</span>
                            <span class="bg-purple-100 text-purple-600 px-2 py-1 rounded-full text-xs font-medium">{{ $attendance_overtimes->total() }}</span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Attendance Table -->
                    <div x-show="activeTab === 'attendance'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nhân viên</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ca làm</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($attendance_shifts as $shift)
                                        @if(is_object($shift) && isset($shift->user))
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img src="/storage/{{ $shift->user->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-gray-300">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $shift->user->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $shift->shift->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ $shift->start_time }}</div>
                                                <div class="text-gray-400">{{ $shift->end_time }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $shift->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($shift->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $shift->status === 'completed' ? 'Hoàn thành' : 
                                                       ($shift->status === 'in_progress' ? 'Đang làm' : 'Chưa bắt đầu') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <button data-user='@json($shift)' class="openDetailModal inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Chi tiết
                                                    </button>
                                                    <a href="javascript:void(0);" data-attendance='@json($shift)' class="openEditModal inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Sửa
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($attendance_shifts->hasPages())
                        <div class="flex items-center justify-between mt-6">
                            <div class="text-sm text-gray-700">
                                Hiển thị {{ $attendance_shifts->firstItem() ?? 0 }} đến {{ $attendance_shifts->lastItem() ?? 0 }} của {{ $attendance_shifts->total() }} kết quả
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($attendance_shifts->onFirstPage())
                                    <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed text-sm">
                                        Trước
                                    </button>
                                @else
                                    <a href="{{ $attendance_shifts->previousPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                        Trước
                                    </a>
                                @endif
                                
                                @foreach($attendance_shifts->getUrlRange(1, min(5, $attendance_shifts->lastPage())) as $page => $url)
                                    <a href="{{ $url }}" 
                                       class="px-3 py-1 rounded-md text-sm {{ $page == $attendance_shifts->currentPage() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                                
                                @if($attendance_shifts->hasMorePages())
                                    <a href="{{ $attendance_shifts->nextPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                        Tiếp
                                    </a>
                                @else
                                    <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed text-sm">
                                        Tiếp
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Overtime Table -->
                    <div x-show="activeTab === 'overtime'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nhân viên</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ca làm</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($attendance_overtimes as $shift)
                                        @if(is_object($shift) && isset($shift->user))
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img src="/storage/{{ $shift->user->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-gray-300">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $shift->user->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $shift->overtimeShift->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>{{ $shift->start_time }}</div>
                                                <div class="text-gray-400">{{ $shift->end_time }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $shift->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($shift->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $shift->status === 'completed' ? 'Hoàn thành' : 
                                                       ($shift->status === 'in_progress' ? 'Đang làm' : 'Chưa bắt đầu') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <button data-attendance='@json($shift)' class="openDetailOvertimeModal inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded-md hover:bg-purple-200 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Chi tiết
                                                    </button>
                                                    <a href="javascript:void(0);" data-attendance='@json($shift)' class="openEditOvertimeModal inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Sửa
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($attendance_overtimes->hasPages())
                        <div class="flex items-center justify-between mt-6">
                            <div class="text-sm text-gray-700">
                                Hiển thị {{ $attendance_overtimes->firstItem() ?? 0 }} đến {{ $attendance_overtimes->lastItem() ?? 0 }} của {{ $attendance_overtimes->total() }} kết quả
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($attendance_overtimes->onFirstPage())
                                    <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed text-sm">
                                        Trước
                                    </button>
                                @else
                                    <a href="{{ $attendance_overtimes->previousPageUrl() }}" class="px-3 py-1 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm">
                                        Trước
                                    </a>
                                @endif
                                
                                @foreach($attendance_overtimes->getUrlRange(1, min(5, $attendance_overtimes->lastPage())) as $page => $url)
                                    <a href="{{ $url }}" 
                                       class="px-3 py-1 rounded-md text-sm {{ $page == $attendance_overtimes->currentPage() ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                                
                                @if($attendance_overtimes->hasMorePages())
                                    <a href="{{ $attendance_overtimes->nextPageUrl() }}" class="px-3 py-1 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm">
                                        Tiếp
                                    </a>
                                @else
                                    <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed text-sm">
                                        Tiếp
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>