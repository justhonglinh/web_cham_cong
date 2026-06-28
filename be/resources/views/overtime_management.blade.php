<x-app-layout>
    <x-slot name="header">
        <x-overtime-model />
    </x-slot>
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-pink-600 via-rose-600 to-pink-800 -mt-6 -mx-6 px-6 py-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Quản lý yêu cầu làm thêm giờ</h1>
                    <p class="text-pink-100 text-lg">Quản lý và phê duyệt yêu cầu tăng ca</p>
                </div>
                
                <!-- Add Overtime Button -->
                <div class="flex items-center space-x-4">
                    <button id="openCreateOvertimeModal"
                        class="flex items-center gap-2 bg-white text-pink-600 hover:bg-pink-50 font-semibold px-6 py-3 rounded-xl shadow-lg transition-all text-base whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tạo yêu cầu tăng ca
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-pink-100 rounded-xl">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Tổng ca tăng</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overtimeShifts->count() }}</p>
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
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Đang chờ duyệt</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overtimeShifts->sum(function($shift) { return $shift->overtimeRequests->where('status', 'pending')->count(); }) }}</p>
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
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Đã phê duyệt</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overtimeShifts->sum(function($shift) { return $shift->overtimeRequests->where('status', 'approved')->count(); }) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-xl">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Đã từ chối</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overtimeShifts->sum(function($shift) { return $shift->overtimeRequests->where('status', 'rejected')->count(); }) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overtime Shifts List -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Danh sách ca tăng và yêu cầu
                    </h3>
                    <span class="bg-pink-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ $overtimeShifts->count() }} ca tăng</span>
                </div>
            </div>
            
            <div class="p-6">
                @php
                    function formatDateTime($datetime) {
                        return \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i');
                    }
                @endphp

                @forelse ($overtimeShifts as $shift)
                    <div class="mb-6 bg-gray-50 dark:bg-gray-700 rounded-xl p-6 hover:shadow-lg transition-all duration-200 relative border border-gray-200 dark:border-gray-600">
                        <!-- Action Buttons -->
                        <div class="absolute top-4 right-4 flex space-x-2">
                            <button
                                type="button"
                                data-shift='@json($shift->toArray() + ["date" => $shift->date ? $shift->date->setTimezone("Asia/Ho_Chi_Minh")->format("Y-m-d") : null])'
                                class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200 shadow-sm"
                                title="Chỉnh sửa" aria-label="Chỉnh sửa ca làm"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>

                            <form action="{{ route('overtime.destroy', $shift->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 shadow-sm"
                                        title="Xóa" aria-label="Xóa ca làm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Shift Header -->
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-rose-500 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-xl text-gray-900 dark:text-white">Ca làm: {{ $shift->name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $shift->description }}</p>
                            </div>
                        </div>

                        <!-- Shift Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="bg-white dark:bg-gray-600 rounded-lg p-3 border border-gray-200 dark:border-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-pink-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Thời gian:</span>
                                </div>
                                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ \Carbon\Carbon::parse($shift->date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</p>
                            </div>
                            
                            <div class="bg-white dark:bg-gray-600 rounded-lg p-3 border border-gray-200 dark:border-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Đăng ký:</span>
                                </div>
                                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $shift->current_registrations }}/{{ $shift->max_registrations }}</p>
                            </div>
                            
                            <div class="bg-white dark:bg-gray-600 rounded-lg p-3 border border-gray-200 dark:border-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái:</span>
                                </div>
                                <p class="text-sm text-gray-900 dark:text-white mt-1">
                                    @if($shift->isFull)
                                        <span class="text-red-600 font-medium">Đã đầy</span>
                                    @else
                                        <span class="text-green-600 font-medium">Còn chỗ</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- All Requests Section -->
                        @if($shift->overtimeRequests->isEmpty())
                            <div class="text-center py-6">
                                <svg class="w-12 h-12 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-yellow-600 font-semibold text-base">Chưa có ai đăng ký ca này!</p>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có yêu cầu làm thêm giờ nào.</p>
                            </div>
                        @else
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="flex items-center justify-between w-full mb-4 px-4 py-3 bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 focus:outline-none transition-colors">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Danh sách yêu cầu ({{ $shift->overtimeRequests->count() }})
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $shift->overtimeRequests->where('status', 'pending')->count() }} chờ duyệt</span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $shift->overtimeRequests->where('status', 'approved')->count() }} đã duyệt</span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">{{ $shift->overtimeRequests->where('status', 'rejected')->count() }} từ chối</span>
                                    </span>
                                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open }"
                                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition class="mt-4">
                                    <div class="bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500 overflow-hidden">
                                        <table class="w-full">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nhân viên</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thời gian tạo</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trạng thái</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-500">
                                                @foreach ($shift->overtimeRequests as $request)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors">
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center">
                                                                @if($request->user && $request->user->avatar)
                                                                    <img src="{{ asset('storage/' . $request->user->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 shadow-sm mr-3" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                                    <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-rose-500 rounded-full flex items-center justify-center mr-3" style="display: none;">
                                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                        </svg>
                                                                    </div>
                                                                @else
                                                                    <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-rose-500 rounded-full flex items-center justify-center mr-3">
                                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                        </svg>
                                                                    </div>
                                                                @endif
                                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $request->user->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                                            {{ \Carbon\Carbon::parse($request->created_at)->format('H:i d/m/Y') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-center">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                                                @elseif($request->status === 'approved') bg-green-100 text-green-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                                {{ $request->status_text }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3 text-center">
                                                            @if($request->status === 'pending')
                                                                <div class="flex items-center justify-center space-x-2">
                                                                    <form method="POST" action="{{ route('overtimeRequests.update', $request->id) }}" class="inline-block">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="approved" />
                                                                        <button type="submit"
                                                                                class="px-3 py-1 rounded-lg text-white text-sm bg-green-500 hover:bg-green-600 transition-colors">
                                                                            Phê duyệt
                                                                        </button>
                                                                    </form>

                                                                    <form method="POST" action="{{ route('overtimeRequests.update', $request->id) }}" class="inline-block">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="rejected" />
                                                                        <button type="submit"
                                                                                class="px-3 py-1 rounded-lg text-white text-sm bg-red-500 hover:bg-red-600 transition-colors">
                                                                            Từ chối
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @else
                                                                <span class="text-sm text-gray-500">
                                                                    @if($request->status === 'approved')
                                                                        Đã phê duyệt lúc {{ \Carbon\Carbon::parse($request->updated_at)->format('H:i d/m/Y') }}
                                                                    @else
                                                                        Đã từ chối lúc {{ \Carbon\Carbon::parse($request->updated_at)->format('H:i d/m/Y') }}
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Chưa có ca làm thêm giờ nào.</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Hãy tạo ca làm thêm giờ đầu tiên</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

