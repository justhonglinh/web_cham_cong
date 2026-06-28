<x-app-layout>
    <div class="py-6 sm:py-8 lg:py-12" style="background: linear-gradient(135deg, #e0e7ff 0%, #fdf2f8 50%, #e0f2fe 100%); min-height: calc(100vh - 64px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="bg-white/95 rounded-3xl shadow-2xl p-6 sm:p-8 border border-orange-200/50 backdrop-blur-xl mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">📝 Đăng ký thay đổi lịch làm việc</h1>
                        <p class="text-gray-600 text-base sm:text-lg">Gửi yêu cầu đi muộn/nghỉ phép/về sớm cho quản lý phê duyệt</p>
                    </div>
                    
                    <!-- Action Button -->
                    <a href="{{ route('employees.leave.history') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Xem lịch sử
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Form Section -->
                    <div class="lg:col-span-2">
                        <div class="bg-white/95 rounded-3xl shadow-xl border border-orange-200/50 backdrop-blur-xl overflow-hidden">
                            <!-- Form Header -->
                            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">Thông tin yêu cầu</h3>
                                        <p class="text-orange-100 text-sm">Điền đầy đủ thông tin bên dưới</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Content -->
                            <div class="p-6 sm:p-8">
                                <form method="POST" action="{{ route('employees.leave.store') }}" class="space-y-6">
                                    @csrf

                                    <!-- Loại yêu cầu -->
                                    <div>
                                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Loại yêu cầu *</label>
                                        <select id="type" name="type" class="w-full border-gray-300 rounded-xl focus:border-orange-500 focus:ring-orange-500 shadow-sm transition-colors" required>
                                            <option value="">Chọn loại yêu cầu</option>
                                            <option value="late" {{ old('type') == 'late' ? 'selected' : '' }}>⏰ Đi muộn</option>
                                            <option value="leave" {{ old('type') == 'leave' ? 'selected' : '' }}>🏖️ Nghỉ phép</option>
                                            <option value="early_leave" {{ old('type') == 'early_leave' ? 'selected' : '' }}>🚪 Về sớm</option>
                                        </select>
                                        @error('type')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Ngày yêu cầu -->
                                    <div>
                                        <label for="request_date" class="block text-sm font-semibold text-gray-700 mb-2">Ngày yêu cầu *</label>
                                        <input id="request_date" name="request_date" type="date" 
                                               class="w-full border-gray-300 rounded-xl focus:border-orange-500 focus:ring-orange-500 shadow-sm transition-colors" 
                                               value="{{ old('request_date') }}" 
                                               required 
                                               min="{{ date('Y-m-d') }}" />
                                        @error('request_date')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Thời gian bắt đầu (cho đi muộn/về sớm) -->
                                    <div id="time_fields" class="hidden space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">Thời gian đến</label>
                                                <input id="start_time" name="start_time" type="time" 
                                                       class="w-full border-gray-300 rounded-xl focus:border-orange-500 focus:ring-orange-500 shadow-sm transition-colors" 
                                                       value="{{ old('start_time') }}" />
                                                @error('start_time')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">Thời gian về</label>
                                                <input id="end_time" name="end_time" type="time" 
                                                       class="w-full border-gray-300 rounded-xl focus:border-orange-500 focus:ring-orange-500 shadow-sm transition-colors" 
                                                       value="{{ old('end_time') }}" />
                                                @error('end_time')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lý do -->
                                    <div>
                                        <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">Lý do *</label>
                                        <textarea id="reason" name="reason" rows="4" 
                                                  class="w-full border-gray-300 rounded-xl focus:border-orange-500 focus:ring-orange-500 shadow-sm transition-colors resize-none" 
                                                  placeholder="Vui lòng nêu rõ lý do yêu cầu nghỉ phép..." 
                                                  required>{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-6 border-t border-gray-200">
                                        <a href="{{ route('employees.leave.history') }}" 
                                           class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-center">
                                            ❌ Hủy bỏ
                                        </a>
                                        <button type="submit" 
                                                class="w-full sm:w-auto bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-3 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold">
                                            📤 Gửi yêu cầu
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Info Cards Section -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">📋 Hướng dẫn loại yêu cầu</h3>
                        
                        <!-- Đi muộn -->
                        <div class="bg-white/95 rounded-2xl shadow-lg p-6 border border-yellow-200/50 backdrop-blur-xl">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">⏰ Đi muộn</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Yêu cầu đi muộn với thời gian cụ thể. Cần chọn thời gian đến và thời gian về.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nghỉ phép -->
                        <div class="bg-white/95 rounded-2xl shadow-lg p-6 border border-red-200/50 backdrop-blur-xl">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">🏖️ Nghỉ phép</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Yêu cầu nghỉ phép cả ngày. Không cần chọn thời gian cụ thể.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Về sớm -->
                        <div class="bg-white/95 rounded-2xl shadow-lg p-6 border border-blue-200/50 backdrop-blur-xl">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">🚪 Về sớm</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Yêu cầu về sớm với thời gian cụ thể. Cần chọn thời gian đến và thời gian về.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            const timeFields = document.getElementById('time_fields');
            const startTime = document.getElementById('start_time');
            const endTime = document.getElementById('end_time');
            
            if (this.value === 'late' || this.value === 'early_leave') {
                timeFields.classList.remove('hidden');
                startTime.required = true;
                endTime.required = true;
            } else {
                timeFields.classList.add('hidden');
                startTime.required = false;
                endTime.required = false;
            }
        });
    </script>
</x-app-layout> 