<!-- Detail Overtime Modal -->
<div id="attendanceOvertimeDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-xl mx-4 relative overflow-hidden max-h-[80vh] overflow-y-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Thông tin tăng ca
                </h2>
                <button type="button" data-modal-hide="attendanceOvertimeDetailModal" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- User Info Card -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-xl p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="attendanceOvertimeDetailUserName">Tên nhân viên</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300" id="attendanceOvertimeDetailUserEmail">email@example.com</p>
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Ảnh đại diện
                    </h4>
                    <div class="aspect-square bg-gray-100 dark:bg-gray-600 rounded-lg overflow-hidden">
                        <img id="attendanceOvertimeDetailUserAvatar" class="w-full h-full object-cover" src="" alt="User Avatar">
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ảnh chấm công
                    </h4>
                    <div class="aspect-square bg-gray-100 dark:bg-gray-600 rounded-lg overflow-hidden">
                        <img id="attendanceOvertimeDetailFaceImage" class="w-full h-full object-cover" src="" alt="Face Image">
                    </div>
                </div>
            </div>

            <!-- Overtime Details -->
            <div class="bg-white dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-600 px-4 py-3">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Chi tiết tăng ca
                    </h4>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white" id="attendanceOvertimeDetailDate">--</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ca làm</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white" id="attendanceOvertimeDetailShift">--</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ vào</p>
                            <p class="text-sm font-semibold text-green-600 dark:text-green-400" id="attendanceOvertimeDetailCheckIn">--</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ ra</p>
                            <p class="text-sm font-semibold text-red-600 dark:text-red-400" id="attendanceOvertimeDetailCheckOut">--</p>
                        </div>
                        <div class="col-span-2 space-y-2">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</p>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full" id="attendanceOvertimeDetailStatus">--</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-2">
            <!-- Footer content can be added here if needed -->
        </div>
    </div>
</div>

<!-- Edit Overtime Modal -->
<div id="attendanceOvertimeEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg mx-4 relative overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Sửa thông tin tăng ca
                </h2>
                <button type="button" data-modal-hide="attendanceOvertimeEditModal" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Form -->
        <form id="editAttendanceOvertimeForm" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="editAttendanceOvertimeId" name="id">
            <input type="hidden" id="editAttendanceOvertimeUserId" name="user_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="editAttendanceOvertimeDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ngày</label>
                    <input type="date" id="editAttendanceOvertimeDate" name="date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-400">
                </div>

                <div>
                    <label for="editAttendanceOvertimeShiftId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ca làm</label>
                    <select id="editAttendanceOvertimeShiftId" name="overtime_shift_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-400">
                        <option value="">Chọn ca làm</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="editAttendanceOvertimeCheckIn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Giờ vào</label>
                    <input type="time" id="editAttendanceOvertimeCheckIn" name="check_in_time"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-400">
                </div>

                <div>
                    <label for="editAttendanceOvertimeCheckOut" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Giờ ra</label>
                    <input type="time" id="editAttendanceOvertimeCheckOut" name="check_out_time"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-400">
                </div>
            </div>

            <div>
                <label for="editAttendanceOvertimeStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trạng thái</label>
                <select id="editAttendanceOvertimeStatus" name="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-400">
                    <option value="present">Đã chấm công</option>
                    <option value="absent">Vắng mặt</option>
                    <option value="late">Đi muộn</option>
                    <option value="early_leave">Về sớm</option>
                    <option value="leave">Nghỉ phép</option>
                </select>
            </div>
        </form>

        <!-- Footer -->
        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end space-x-3">
            <button type="button" data-modal-hide="attendanceOvertimeEditModal" class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-500 transition-colors">
                Huỷ
            </button>
            <button type="submit" form="editAttendanceOvertimeForm" class="px-6 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:bg-purple-500 dark:hover:bg-purple-600 transition-colors">
                Lưu thay đổi
            </button>
        </div>
    </div>
</div> 