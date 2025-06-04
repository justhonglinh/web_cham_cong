<!-- resources/views/overtime_shifts/modal_create.blade.php -->
<div id="createOvertimeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-4">Tạo Yêu Cầu Làm Thêm Giờ</h2>
        <form method="POST" action="{{ route('overtime.store') }}">
            @csrf

            <div class="mb-4">
                <label for="overtimeName" class="block text-sm font-medium text-gray-700">Tên Dự Án</label>
                <input type="text" id="overtimeName" name="name" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="overtimeStart" class="block text-sm font-medium text-gray-700">Thời Gian Bắt Đầu</label>
                <input type="time" id="overtimeStart" name="start_time" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="overtimeEnd" class="block text-sm font-medium text-gray-700">Thời Gian Kết Thúc</label>
                <input type="time" id="overtimeEnd" name="end_time" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="overtimeDescription" class="block text-sm font-medium text-gray-700">Mô Tả</label>
                <textarea id="overtimeDescription" name="description"
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                                 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>

            <div class="mb-4">
                <label for="overtimeMax" class="block text-sm font-medium text-gray-700">Số Lượng Đăng Ký Tối Đa</label>
                <input type="number" id="overtimeMax" name="max_registrations" required min="1"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-6">
                <label for="overtimeDate" class="block text-sm font-medium text-gray-700">Ngày Bắt Đầu</label>
                <input type="date" id="overtimeDate" name="date" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition duration-300">Xác Nhận
                </button>
                <button type="button" id="closeCreateOvertimeModal"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">Huỷ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- resources/views/overtime_shifts/modal_edit.blade.php -->
<div id="editOvertimeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-4">Sửa Ca Làm Thêm Giờ</h2>
        <form id="editOvertimeForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Tên Ca</label>
                <input type="text" id="edit_name" name="name" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>
            <div class="mb-4">
                <label for="edit_start_time" class="block text-sm font-medium text-gray-700">Bắt Đầu</label>
                <input type="time" id="edit_start_time" name="start_time" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>
            <div class="mb-4">
                <label for="edit_end_time" class="block text-sm font-medium text-gray-700">Kết Thúc</label>
                <input type="time" id="edit_end_time" name="end_time" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>
            <div class="mb-4">
                <label for="edit_description" class="block text-sm font-medium text-gray-700">Mô Tả</label>
                <textarea id="edit_description" name="description"
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                                 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="edit_max_registrations" class="block text-sm font-medium text-gray-700">Số Lượng Đăng Ký Tối Đa</label>
                <input type="number" id="edit_max_registrations" name="max_registrations" required min="1"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>
            <div class="mb-6">
                <label for="edit_date" class="block text-sm font-medium text-gray-700">Ngày</label>
                <input type="date" id="edit_date" name="date" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>
            <div class="flex justify-between items-center">
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition duration-300">
                    Lưu thay đổi
                </button>
                <button type="button" id="closeEditOvertimeModal"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">
                    Huỷ
                </button>
            </div>
        </form>
    </div>
</div>
