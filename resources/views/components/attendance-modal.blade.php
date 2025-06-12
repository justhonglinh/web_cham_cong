<!-- Detail Attendance Modal -->
<div id="userDetailModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center hidden z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-md relative">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center">Thông tin chấm công</h2>

        <div class="flex flex-col items-center mb-6">
            <img id="detailAvatar" src="" alt="Avatar"
                 class="w-24 h-24 rounded-full object-cover shadow-md border border-gray-300 mb-4">
            <div class="text-center">
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200" id="detailName"></p>
            </div>
        </div>

        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg space-y-2">
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center flex-wrap gap-x-4 gap-y-1">
                <strong>Ngày:</strong>
                <span id="detailDate" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center flex-wrap gap-x-4 gap-y-1">
                <strong>Ca làm:</strong>
                <span id="detailShift" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center flex-wrap gap-x-4 gap-y-1">
                <strong>Giờ vào:</strong>
                <span id="detailCheckIn" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center flex-wrap gap-x-4 gap-y-1">
                <strong>Giờ ra:</strong>
                <span id="detailCheckOut" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center flex-wrap gap-x-4 gap-y-1">
                <strong>Trạng thái:</strong>
                <span id="detailStatus" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
        </div>

        <div class="mt-4">
            <img id="detailFaceImage" src="" alt="Face Image" class="w-full h-auto rounded-lg shadow-md">
        </div>

        <div class="mt-6 text-right">
            <button id="closeDetailModal"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500 transition duration-200">
                Đóng
            </button>
        </div>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3 max-w-lg">
        <h2 class="text-xl font-semibold mb-4">Chỉnh Sửa Thông Tin Chấm Công</h2>

        <form id="editForm" action="" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="editAttendanceId" />

            <div class="mb-4">
                <label for="editDate" class="block text-sm font-medium text-gray-700">Ngày</label>
                <input type="date" id="editDate" name="date" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editShift" class="block text-sm font-medium text-gray-700">Ca làm</label>
                <select id="editShift" name="shift_id" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500">
                    
                </select>
            </div>

            <div class="mb-4">
                <label for="editCheckIn" class="block text-sm font-medium text-gray-700">Giờ vào</label>
                <input type="time" id="editCheckIn" name="check_in_time"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editCheckOut" class="block text-sm font-medium text-gray-700">Giờ ra</label>
                <input type="time" id="editCheckOut" name="check_out_time"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editStatus" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                <select id="editStatus" name="status" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Chọn trạng thái</option>
                    <option value="present">Đã chấm công</option>
                    <option value="absent">Vắng mặt</option>
                    <option value="leave">Nghỉ phép</option>
                    <option value="late">Đi muộn</option>
                    <option value="early_leave">Về sớm</option>
                </select>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300">
                    Xác Nhận
                </button>
                <button type="button" id="cancelEditBtn"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">
                    Huỷ
                </button>
            </div>
        </form>
    </div>
</div> 