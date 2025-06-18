<!-- Modal chi tiết tăng ca -->
<div id="attendanceOvertimeDetailModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center hidden z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-md relative">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center">Thông tin tăng ca</h2>

        <div class="relative p-6">
            <div class="flex items-center gap-4 mb-6">
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

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày</p>
                    <p class="text-base font-medium text-gray-900 dark:text-white" id="attendanceOvertimeDetailDate"></p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ca làm</p>
                    <p class="text-base font-medium text-gray-900 dark:text-white" id="attendanceOvertimeDetailShift"></p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ vào</p>
                    <p class="text-base font-medium text-gray-900 dark:text-white" id="attendanceOvertimeDetailCheckIn"></p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Giờ ra</p>
                    <p class="text-base font-medium text-gray-900 dark:text-white" id="attendanceOvertimeDetailCheckOut"></p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                    <p class="text-base font-medium" id="attendanceOvertimeDetailStatus"></p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-6">
            <button type="button" data-modal-hide="attendanceOvertimeDetailModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                Đóng
            </button>
        </div>
    </div>
</div>

<!-- Modal sửa tăng ca -->
<div id="attendanceOvertimeEditModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center hidden z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-md relative">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center">Sửa thông tin tăng ca</h2>
        <form id="editAttendanceOvertimeForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="editAttendanceOvertimeId" name="id">
            <input type="hidden" id="editAttendanceOvertimeUserId" name="user_id">

            <div>
                <label for="editAttendanceOvertimeDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ngày</label>
                <input type="date" id="editAttendanceOvertimeDate" name="date"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="editAttendanceOvertimeShiftId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ca làm</label>
                <select id="editAttendanceOvertimeShiftId" name="overtime_shift_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Chọn ca làm</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="editAttendanceOvertimeCheckIn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giờ vào</label>
                <input type="time" id="editAttendanceOvertimeCheckIn" name="check_in_time"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="editAttendanceOvertimeCheckOut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giờ ra</label>
                <input type="time" id="editAttendanceOvertimeCheckOut" name="check_out_time"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="editAttendanceOvertimeStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                <select id="editAttendanceOvertimeStatus" name="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="present">Đã chấm công</option>
                    <option value="absent">Vắng mặt</option>
                    <option value="late">Đi muộn</option>
                    <option value="early_leave">Về sớm</option>
                    <option value="leave">Nghỉ phép</option>
                </select>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" id="cancelAttendanceOvertimeEditModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    Huỷ
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div> 