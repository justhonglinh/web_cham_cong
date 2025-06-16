<!-- Modal chi tiết chấm công tăng ca -->
<div id="attendanceOvertimeDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" aria-modal="true">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Chi tiết chấm công tăng ca</h3>
                <button id="closeAttendanceOvertimeDetailModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex items-center space-x-4 mb-4">
                <div class="flex-shrink-0">
                    <img id="attendanceOvertimeDetailUserAvatar" class="h-24 w-24 rounded-lg object-cover border-2 border-gray-200" src="" alt="User Avatar">
                </div>
                <div class="flex-shrink-0">
                    <img id="attendanceOvertimeDetailFaceImage" class="h-24 w-24 rounded-lg object-cover border-2 border-gray-200" src="" alt="Face Image">
                </div>
                <div class="flex-grow">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="attendanceOvertimeDetailUserName"></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="attendanceOvertimeDetailUserEmail"></p>
                </div>
            </div>
            <div class="mt-2 px-7 py-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Ngày:</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white" id="attendanceOvertimeDetailDate"></span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Ca làm:</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white" id="attendanceOvertimeDetailShift"></span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Giờ vào:</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white" id="attendanceOvertimeDetailCheckIn"></span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Giờ ra:</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white" id="attendanceOvertimeDetailCheckOut"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Trạng thái:</span>
                    <span class="text-sm font-medium" id="attendanceOvertimeDetailStatus"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa chấm công tăng ca -->
<div id="attendanceOvertimeEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" aria-modal="true">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Sửa thông tin chấm công tăng ca</h3>
                <button id="closeAttendanceOvertimeEditModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editAttendanceOvertimeForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editAttendanceOvertimeId" name="id">
                <input type="hidden" id="editAttendanceOvertimeUserId" name="user_id">
                
                <div class="mb-4">
                    <label for="editAttendanceOvertimeDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ngày</label>
                    <input type="date" id="editAttendanceOvertimeDate" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="editAttendanceOvertimeShiftId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ca làm</label>
                    <select id="editAttendanceOvertimeShiftId" name="overtime_shift_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Chọn ca làm</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="editAttendanceOvertimeCheckIn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giờ vào</label>
                    <input type="time" id="editAttendanceOvertimeCheckIn" name="check_in_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="editAttendanceOvertimeCheckOut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giờ ra</label>
                    <input type="time" id="editAttendanceOvertimeCheckOut" name="check_out_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="editAttendanceOvertimeStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                    <select id="editAttendanceOvertimeStatus" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="present">Đã chấm công</option>
                        <option value="absent">Vắng mặt</option>
                        <option value="late">Đi muộn</option>
                        <option value="early_leave">Về sớm</option>
                        <option value="leave">Nghỉ phép</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="closeAttendanceOvertimeEditModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Huỷ
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 