<x-app-layout>
    <div class="py-6 sm:py-8 lg:py-12" style="background: linear-gradient(135deg, #e0e7ff 0%, #fdf2f8 50%, #e0f2fe 100%); min-height: calc(100vh - 64px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="bg-white/95 rounded-3xl shadow-2xl p-6 sm:p-8 border border-blue-200/50 backdrop-blur-xl mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">📊 Lịch sử chấm công</h1>
                        <p class="text-gray-600 text-base sm:text-lg">Xem chi tiết các ca làm việc và thống kê thời gian</p>
                    </div>

                    <!-- Thống kê tổng quan -->
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-4 border border-blue-200">
                            <div class="text-2xl font-bold text-blue-600">{{ $attendances->count() }}</div>
                            <div class="text-sm text-blue-700">Tổng ca làm</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-4 border border-green-200">
                            <div class="text-2xl font-bold text-green-600">{{ $attendances->where('status', 'success')->count() }}</div>
                            <div class="text-sm text-green-700">Thành công</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-4 border border-purple-200">
                            <div class="text-2xl font-bold text-purple-600">{{ $attendances->where('status', 'overtime')->count() }}</div>
                            <div class="text-sm text-purple-700">Overtime</div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-4 border border-indigo-200">
                            @php
                                $totalWorkMinutes = 0;
                                foreach($attendances as $attendance) {
                                    if($attendance->check_in_time && $attendance->check_out_time) {
                                        $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                        $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
                                        $totalWorkMinutes += $checkIn->diffInMinutes($checkOut);
                                    }
                                }

                                // Làm tròn tổng thời gian đến 15 phút gần nhất
                                $roundedTotalMinutes = round($totalWorkMinutes / 15) * 15;
                                $totalWorkHours = intval($roundedTotalMinutes / 60);
                                $remainingTotalMinutes = $roundedTotalMinutes % 60;
                            @endphp
                            <div class="text-2xl font-bold text-indigo-600">
                                @if($totalWorkHours > 0)
                                    {{ $totalWorkHours }}h
                                    @if($remainingTotalMinutes > 0)
                                        {{ $remainingTotalMinutes }}m
                                    @endif
                                @else
                                    {{ $remainingTotalMinutes }}m
                                @endif
                            </div>
                            <div class="text-sm text-indigo-700">Tổng thời gian</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách ca làm việc -->
            <div class="bg-white/95 rounded-3xl shadow-xl border border-blue-200/50 backdrop-blur-xl overflow-hidden">
                <!-- Header của bảng -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Danh sách ca làm việc</h3>
                            <p class="text-blue-100">Tháng {{ now()->format('m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 border-b border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Ngày
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 border-b border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Loại ca
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 border-b border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Check In
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 border-b border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Check Out
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 border-b border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Tổng thời gian
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 border-b border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Trạng thái
                                    </div>
                                </th>
                    </tr>
                </thead>
                        <tbody class="divide-y divide-gray-200">
                    @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <!-- Ngày -->
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($attendance->date)->format('l') }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Loại ca -->
                                <td class="px-6 py-4">
                                    @if($attendance->overtime_id)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-purple-700">Làm thêm</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-blue-700">Ca chính</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Check In -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '--:--' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Check Out -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '--:--' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Tổng thời gian -->
                                <td class="px-6 py-4">
                                    @if($attendance->check_in_time && $attendance->check_out_time)
                                        @php
                                            $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                            $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
                                            $totalMinutes = $checkIn->diffInMinutes($checkOut);

                                            // Làm tròn đến 15 phút gần nhất
                                            $roundedMinutes = round($totalMinutes / 15) * 15;
                                            $totalHours = intval($roundedMinutes / 60);
                                            $remainingMinutes = $roundedMinutes % 60;
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">
                                                @if($totalHours > 0)
                                                    {{ $totalHours }}h
                                                    @if($remainingMinutes > 0)
                                                        {{ $remainingMinutes }}m
                                                    @endif
                                                @else
                                                    {{ $remainingMinutes }}m
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-gray-500">--:--</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Trạng thái -->
                                <td class="px-6 py-4">
                                @if($attendance->status == 'present')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Đã Chấm Công
                                        </span>
                                    @elseif($attendance->status == 'early_leave')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Về Sớm
                                        </span>
                                    @elseif($attendance->status == 'leave')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Nghỉ
                                        </span>
                                @elseif($attendance->status == 'late')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Muộn
                                        </span>
                                @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Vắng Mặt
                                        </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                </div>

                <!-- Thông tin bổ sung cho từng row (nếu có) -->
                @foreach($attendances as $attendance)
                    @if($attendance->notes)
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-3">
                        <div class="flex flex-wrap gap-4 text-sm">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $attendance->notes }}</span>
                            </div>
                        </div>
            </div>
                    @endif
                @endforeach
        </div>

            <!-- Nút quay lại -->
            <div class="mt-8 flex justify-center">
                <a href="{{ route('employees.dashboard') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                Quay lại Dashboard
            </a>
            </div>
        </div>
    </div>
</x-app-layout>
