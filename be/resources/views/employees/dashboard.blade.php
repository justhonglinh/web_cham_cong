<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight">
            {{ __('🌟 Dashboard nhân viên 🌟') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12" style="background: linear-gradient(135deg, #e0e7ff 0%, #fdf2f8 50%, #e0f2fe 100%); min-height: calc(100vh - 64px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Card với thông tin cá nhân -->
            <div class="bg-white/95 rounded-3xl shadow-2xl p-6 sm:p-8 lg:p-10 border border-blue-200/50 backdrop-blur-xl mb-8">
                <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6 lg:gap-8">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-bold shadow-xl">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Thông tin cá nhân -->
                    <div class="text-center lg:text-left flex-1">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-600 text-base sm:text-lg mb-3">{{ Auth::user()->email }}</p>
                        <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                Nhân viên
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Đang hoạt động
                            </span>
                        </div>
                    </div>

                    <!-- Thời gian hiện tại -->
                    <div class="text-center lg:text-right bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-4 sm:p-6 border border-blue-100">
                        <div class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1" id="currentTime">--:--:--</div>
                        <div class="text-base text-gray-600" id="currentDate">--/--/----</div>
                    </div>
                </div>
            </div>

            <!-- Main Functions Grid - 4 icon chính -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Chấm công -->
                <a href="{{ route('employees.attendance') }}" 
                   class="group bg-white/95 rounded-3xl p-6 shadow-xl hover:shadow-2xl border border-green-200/50 backdrop-blur-xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 h-full">
                    <div class="flex flex-col items-center text-center h-full justify-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-3xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200 shadow-lg">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">📸 Chấm công</h3>
                        <p class="text-gray-600 text-sm">Nhận diện khuôn mặt</p>
                    </div>
                </a>

                <!-- Request làm thêm giờ -->
                <a href="{{ route('employees.overtime.index') }}" 
                   class="group bg-white/95 rounded-3xl p-6 shadow-xl hover:shadow-2xl border border-purple-200/50 backdrop-blur-xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 h-full">
                    <div class="flex flex-col items-center text-center h-full justify-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200 shadow-lg">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">⚡ Làm thêm giờ</h3>
                        <p class="text-gray-600 text-sm">Đăng ký làm thêm giờ</p>
                    </div>
                </a>

                <!-- Lịch sử chấm công -->
                <a href="{{ route('employees.attendance.history') }}" 
                   class="group bg-white/95 rounded-3xl p-6 shadow-xl hover:shadow-2xl border border-blue-200/50 backdrop-blur-xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 h-full">
                    <div class="flex flex-col items-center text-center h-full justify-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200 shadow-lg">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">📊 Lịch sử</h3>
                        <p class="text-gray-600 text-sm">Xem lịch sử chấm công</p>
                    </div>
                </a>

                <!-- Nghỉ phép -->
                <a href="{{ route('employees.leave.request') }}" 
                   class="group bg-white/95 rounded-3xl p-6 shadow-xl hover:shadow-2xl border border-orange-200/50 backdrop-blur-xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 h-full">
                    <div class="flex flex-col items-center text-center h-full justify-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200 shadow-lg">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">📝 Nghỉ phép</h3>
                        <p class="text-gray-600 text-sm">Yêu cầu nghỉ</p>
                    </div>
                </a>
            </div>

            <!-- Thống kê nhanh -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white/95 rounded-3xl p-6 shadow-xl border border-blue-200/50 backdrop-blur-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Giờ làm hôm nay</p>
                            <p class="text-xl font-bold text-gray-900">8.5h</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 rounded-3xl p-6 shadow-xl border border-green-200/50 backdrop-blur-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Chấm công thành công</p>
                            <p class="text-xl font-bold text-gray-900">22/30</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 rounded-3xl p-6 shadow-xl border border-yellow-200/50 backdrop-blur-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-2xl">
                            <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tăng ca tháng này</p>
                            <p class="text-xl font-bold text-gray-900">12h</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 rounded-3xl p-6 shadow-xl border border-purple-200/50 backdrop-blur-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Ngày nghỉ còn lại</p>
                            <p class="text-xl font-bold text-gray-900">15</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('vi-VN');
            const dateString = now.toLocaleDateString('vi-VN');
            
            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('currentDate').textContent = dateString;
        }

        // Cập nhật thời gian mỗi giây
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</x-app-layout>