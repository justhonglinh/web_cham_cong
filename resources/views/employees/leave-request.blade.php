<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Đăng ký yêu cầu') }}
            </h2>
        </div>
    </x-slot>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-green-800 -mt-6 -mx-6 px-6 py-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Đăng ký yêu cầu nghỉ phép</h1>
                    <p class="text-green-100 text-lg">Gửi yêu cầu đi muộn/nghỉ phép/về sớm</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('employees.leave.history') }}" class="flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white hover:bg-white/20 font-semibold px-6 py-3 rounded-xl transition-all text-base whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Xem lịch sử
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Thông tin yêu cầu
                    </h3>
                </div>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('employees.leave.store') }}" class="space-y-6">
                    @csrf

                    <!-- Loại yêu cầu -->
                    <div>
                        <x-input-label for="type" :value="__('Loại yêu cầu')" class="text-sm font-medium text-gray-700" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg shadow-sm" required>
                            <option value="">Chọn loại yêu cầu</option>
                            <option value="late" {{ old('type') == 'late' ? 'selected' : '' }}>Đi muộn</option>
                            <option value="leave" {{ old('type') == 'leave' ? 'selected' : '' }}>Nghỉ phép</option>
                            <option value="early_leave" {{ old('type') == 'early_leave' ? 'selected' : '' }}>Về sớm</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- Ngày yêu cầu -->
                    <div>
                        <x-input-label for="request_date" :value="__('Ngày yêu cầu')" class="text-sm font-medium text-gray-700" />
                        <x-text-input id="request_date" name="request_date" type="date" class="mt-1 block w-full" :value="old('request_date')" required min="{{ date('Y-m-d') }}" />
                        <x-input-error :messages="$errors->get('request_date')" class="mt-2" />
                    </div>

                    <!-- Thời gian bắt đầu (cho đi muộn/về sớm) -->
                    <div id="time_fields" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_time" :value="__('Thời gian bắt đầu')" class="text-sm font-medium text-gray-700" />
                                <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" :value="old('start_time')" />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="end_time" :value="__('Thời gian kết thúc')" class="text-sm font-medium text-gray-700" />
                                <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time')" />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Lý do -->
                    <div>
                        <x-input-label for="reason" :value="__('Lý do')" class="text-sm font-medium text-gray-700" />
                        <textarea id="reason" name="reason" rows="4" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg shadow-sm" placeholder="Vui lòng nêu rõ lý do..." required>{{ old('reason') }}</textarea>
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('employees.leave.history') }}" class="mr-4 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Hủy
                        </a>
                        <x-primary-button class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
                            {{ __('Gửi yêu cầu') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="ml-3 font-semibold text-gray-900">Đi muộn</h4>
                </div>
                <p class="text-sm text-gray-600">Yêu cầu đi muộn với thời gian cụ thể. Cần chọn thời gian bắt đầu và kết thúc.</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="ml-3 font-semibold text-gray-900">Nghỉ phép</h4>
                </div>
                <p class="text-sm text-gray-600">Yêu cầu nghỉ phép cả ngày. Không cần chọn thời gian cụ thể.</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="ml-3 font-semibold text-gray-900">Về sớm</h4>
                </div>
                <p class="text-sm text-gray-600">Yêu cầu về sớm với thời gian cụ thể. Cần chọn thời gian bắt đầu và kết thúc.</p>
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