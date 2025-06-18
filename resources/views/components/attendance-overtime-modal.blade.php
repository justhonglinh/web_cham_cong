<!-- Modal chi tiết tăng ca -->
<div id="attendanceOvertimeDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full z-50 w-full hidden" aria-modal="true">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Chi tiết tăng ca</h3>
                <button id="closeAttendanceOvertimeDetailModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3">
                <div class="flex justify-center mb-4">
                    <img id="attendanceOvertimeDetailUserAvatar" class="h-20 w-20 rounded-full" src="" alt="Avatar">
                </div>
                <div class="flex justify-center mb-4">
                    <img id="attendanceOvertimeDetailFaceImage" class="h-40 w-40 rounded-lg" src="" alt="Face Image">
                </div>
                <div class="text-left">
                    <p class="text-sm text-gray-500 mb-2">
                        <span class="font-medium">Tên nhân viên:</span>
                        <span id="attendanceOvertimeDetailUserName"></span>
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <span class="font-medium">Email:</span>
                        <span id="attendanceOvertimeDetailUserEmail"></span>
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <span class="font-medium">Ngày:</span>
                        <span id="attendanceOvertimeDetailDate"></span>
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <span class="font-medium">Ca làm:</span>
                        <span id="attendanceOvertimeDetailShift"></span>
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <span class="font-medium">Giờ vào:</span>
                        <span id="attendanceOvertimeDetailCheckIn"></span>
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <span class="font-medium">Giờ ra:</span>
                        <span id="attendanceOvertimeDetailCheckOut"></span>
                    </p>
                    <p class="text-sm text-gray-500">
                        <span class="font-medium">Trạng thái:</span>
                        <span id="attendanceOvertimeDetailStatus"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa tăng ca -->
<div id="attendanceOvertimeEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" aria-modal="true">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Sửa thông tin tăng ca</h3>
                <button id="closeAttendanceOvertimeEditModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editAttendanceOvertimeForm" class="mt-2 px-7 py-3">
                <input type="hidden" id="editAttendanceOvertimeId" name="id">
                <input type="hidden" id="editAttendanceOvertimeUserId" name="user_id">
                
                <div class="mb-4">
                    <label for="editAttendanceOvertimeDate" class="block text-sm font-medium text-gray-700">Ngày</label>
                    <input type="date" id="editAttendanceOvertimeDate" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="editAttendanceOvertimeShiftId" class="block text-sm font-medium text-gray-700">Ca làm</label>
                    <select id="editAttendanceOvertimeShiftId" name="overtime_shift_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="editAttendanceOvertimeCheckIn" class="block text-sm font-medium text-gray-700">Giờ vào</label>
                    <input type="time" id="editAttendanceOvertimeCheckIn" name="check_in_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="editAttendanceOvertimeCheckOut" class="block text-sm font-medium text-gray-700">Giờ ra</label>
                    <input type="time" id="editAttendanceOvertimeCheckOut" name="check_out_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="editAttendanceOvertimeStatus" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select id="editAttendanceOvertimeStatus" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="present">Đã chấm công</option>
                        <option value="absent">Vắng mặt</option>
                        <option value="late">Đi muộn</option>
                        <option value="early_leave">Về sớm</option>
                        <option value="leave">Nghỉ phép</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelAttendanceOvertimeEditModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 