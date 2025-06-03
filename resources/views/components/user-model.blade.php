<!-- resources/views/components/user-model.blade.php -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-4">Tạo Thông Tin Nhân Viên</h2>

        <!-- Form tạo mới người dùng -->
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">

            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Tên đầy đủ</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium text-gray-700">Ảnh đại diện</label>
                <input
                    required
                    type="file"
                    id="avatar"
                    name="avatar"
                    accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500
               file:mr-4 file:py-2 file:px-4
               file:rounded-full file:border-0
               file:text-sm file:font-semibold
               file:bg-green-50 file:text-green-700
               hover:file:bg-green-100"
                />
            </div>
            <input type="hidden" name="manager" value="{{ Auth::user()->id }}">

            <div class="flex justify-between items-center">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition duration-300">Xác nhận</button>
                <button type="button" id="closeUserModal" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">Huỷ</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="userEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3 max-w-lg">
        <h2 class="text-xl font-semibold mb-4">Chỉnh Sửa Thông Tin</h2>

        <form id="editUserForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="editUserId" />

            <div class="mb-4">
                <label for="editName" class="block text-sm font-medium text-gray-700">Tên Đầy Đủ</label>
                <input type="text" id="editName" name="name" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editEmail" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="editEmail" name="email" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editPassword" class="block text-sm font-medium text-gray-700">Mật Khẩu<small>(để trống nếu không đổi)</small></label>
                <input type="password" id="editPassword" name="password"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editAvatar" class="block text-sm font-medium text-gray-700">Tải ảnh đại diện</label>
                <input type="file" id="editAvatar" name="avatar" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500
                      file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                      file:text-sm file:font-semibold file:bg-green-50 file:text-green-700
                      hover:file:bg-green-100" />
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300">
                    Xác Nhận
                </button>
                <button type="button" id="closeEditModal"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">
                    Huỷ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Detail User Modal -->
<div id="userDetailModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center hidden z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-md relative">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center">Thông tin nhân viên</h2>

        <div class="flex flex-col items-center mb-6">
            <img id="detailAvatar" src="" alt="Avatar"
                 class="w-24 h-24 rounded-full object-cover shadow-md border border-gray-300 mb-4">
            <div class="text-center">
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200" id="detailName"></p>
                <p class="text-sm text-gray-500 dark:text-gray-400" id="detailEmail"></p>
            </div>
        </div>

        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg space-y-2">
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center flex-wrap gap-x-4 gap-y-1">
                <strong>Ngày tạo:</strong>
                <span>
            <span id="detailCreatedDate" class="ml-1 text-gray-800 dark:text-white italic"></span>
        </span>
                <span>
            <span class="text-gray-500">Giờ:</span>
            <span id="detailCreatedTime" class="ml-1 text-gray-800 dark:text-white italic"></span>
        </span>
            </p>
        </div>


        <div class="mt-6 text-right">
            <button id="closeDetailModal"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500 transition duration-200">
                Đóng
            </button>
        </div>
    </div>
</div>


