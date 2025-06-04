<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-8 animate-fadeIn">Tổng hợp thông tin</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mb-12">
                <!-- Card 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex items-center space-x-4
           transform transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl animate-fadeIn delay-100">
                    <div class="p-3 bg-blue-500 text-white rounded-full drop-shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5.121 17.804A13.937 13.937 0 0112 15c2.634 0 5.088.86 7.123 2.324M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $employeesCount }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Người dùng</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex items-center space-x-4
           transform transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl animate-fadeIn delay-200">
                    <div class="p-3 bg-green-500 text-white rounded-full drop-shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 7V3M16 7V3M3 11h18M5 21h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $shiftsCount }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Ca làm việc</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex items-center space-x-4
           transform transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl animate-fadeIn delay-300">
                    <div class="p-3 bg-yellow-500 text-white rounded-full drop-shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3"/>
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $overtimesCount }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Ca tăng ca</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex items-center space-x-4
           transform transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl animate-fadeIn delay-400">
                    <div class="p-3 bg-red-500 text-white rounded-full drop-shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12h6M9 16h6M9 8h6"/>
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $overtimeRequestsCount }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Đơn xin tăng ca</p>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex items-center space-x-4
           transform transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl animate-fadeIn delay-500">
                    <div class="p-3 bg-purple-500 text-white rounded-full drop-shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 10h1l3 8h10l3-8h1"/>
                            <path d="M5 10V6a7 7 0 0114 0v4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $attendancesCount }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Lần chấm công trong hôm nay</p>
                    </div>
                </div>

            </div>

            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6 animate-fadeIn delay-600">Thông báo mới</h3>

            <div class="space-y-6">
                <!-- Đơn xin tăng ca mới -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-[1.05] hover:shadow-xl hover:shadow-blue-300 animate-fadeIn delay-700">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                <span class="text-3xl text-blue-600 dark:text-blue-400">
                    <i class="fas fa-clipboard-list"></i>
                </span>
                        </div>
                        <div class="w-full">
                            <h4 class="font-semibold text-xl text-blue-600 dark:text-blue-400">Đơn xin tăng ca mới</h4>
                            <p class="text-gray-700 dark:text-gray-300">Bạn có 3 đơn xin tăng ca đang chờ duyệt.</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Cập nhật: 01/06/2025</p>

                            <!-- Dropdown nội dung chi tiết -->
                            <details class="mt-3">
                                <summary class="font-semibold text-blue-600 cursor-pointer">Xem chi tiết</summary>
                                <div class="text-gray-600 dark:text-gray-300 mt-2">
                                    <p><strong>Chi tiết:</strong> Đơn xin tăng ca cho nhân viên A, B, C đang chờ phê duyệt.</p>
                                    <p><strong>Thời gian dự kiến:</strong> 10 giờ tối đến 6 giờ sáng.</p>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Chấm công muộn -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-[1.05] hover:shadow-xl hover:shadow-red-300 animate-fadeIn delay-800">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                <span class="text-3xl text-red-600 dark:text-red-400">
                    <i class="fas fa-clock"></i>
                </span>
                        </div>
                        <div class="w-full">
                            <h4 class="font-semibold text-xl text-red-600 dark:text-red-400">Chấm công muộn</h4>
                            <p class="text-gray-700 dark:text-gray-300">Hôm nay có 2 nhân viên chấm công muộn hơn 15 phút.</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Cập nhật: 02/06/2025</p>

                            <!-- Dropdown nội dung chi tiết -->
                            <details class="mt-3">
                                <summary class="font-semibold text-red-600 cursor-pointer">Xem chi tiết</summary>
                                <div class="text-gray-600 dark:text-gray-300 mt-2">
                                    <p><strong>Chi tiết:</strong> Nhân viên A và B đã chấm công muộn vào lúc 8:15 AM.</p>
                                    <p><strong>Hành động:</strong> Cảnh cáo và thông báo để cải thiện việc chấm công đúng giờ.</p>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Thay đổi ca làm việc -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-[1.05] hover:shadow-xl hover:shadow-yellow-300 animate-fadeIn delay-900">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                <span class="text-3xl text-yellow-600 dark:text-yellow-400">
                    <i class="fas fa-briefcase"></i>
                </span>
                        </div>
                        <div class="w-full">
                            <h4 class="font-semibold text-xl text-yellow-600 dark:text-yellow-400">Thay đổi ca làm việc</h4>
                            <p class="text-gray-700 dark:text-gray-300">Ca làm việc ngày 05/06/2025 đã được cập nhật.</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Cập nhật: 31/05/2025</p>

                            <!-- Dropdown nội dung chi tiết -->
                            <details class="mt-3">
                                <summary class="font-semibold text-yellow-600 cursor-pointer">Xem chi tiết</summary>
                                <div class="text-gray-600 dark:text-gray-300 mt-2">
                                    <p><strong>Chi tiết:</strong> Ca làm việc từ 8 AM đến 5 PM đã được điều chỉnh thành 10 AM đến 6 PM.</p>
                                    <p><strong>Lý do:</strong> Để phù hợp với nhu cầu của dự án.</p>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Thông báo nội bộ -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-[1.05] hover:shadow-xl hover:shadow-green-300 animate-fadeIn delay-1000">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                <span class="text-3xl text-green-600 dark:text-green-400">
                    <i class="fas fa-bell"></i>
                </span>
                        </div>
                        <div class="w-full">
                            <h4 class="font-semibold text-xl text-green-600 dark:text-green-400">Thông báo nội bộ</h4>
                            <p class="text-gray-700 dark:text-gray-300">Cuộc họp toàn công ty sẽ diễn ra vào ngày 07/06/2025.</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Cập nhật: 30/05/2025</p>

                            <!-- Dropdown nội dung chi tiết -->
                            <details class="mt-3">
                                <summary class="font-semibold text-green-600 cursor-pointer">Xem chi tiết</summary>
                                <div class="text-gray-600 dark:text-gray-300 mt-2">
                                    <p><strong>Chi tiết:</strong> Cuộc họp sẽ bàn về chiến lược phát triển sản phẩm trong quý tới.</p>
                                    <p><strong>Thời gian:</strong> 9:00 AM - 12:00 PM.</p>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease forwards;
        }
        /* delays handled by utility classes delay-[100|200|...] in Tailwind */
    </style>
</x-app-layout>
