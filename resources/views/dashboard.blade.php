<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

       <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-800">

                        <!-- Form Tìm kiếm và Thêm mới -->
                        <div class="flex justify-between items-center mb-4">
                            <div class="p-6 bg-white dark:bg-gray-800">
                                <!-- Nút "Add New" -->
                                <div class="flex space-x-4">
                                    <a href="#" id="openUserModal" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition ease-in-out duration-300">Add New</a>
                                </div>

                                <!-- Bao gồm Modal -->
                                <x-user-model />

                            </div>
                            <form method="GET" action="" class="flex items-center space-x-2">
                                <input type="text" name="search" class="form-input rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search..." value="">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition ease-in-out duration-300">Search</button>
                            </form>
                        </div>

                        <!-- Bảng dữ liệu -->
                        <table class="min-w-full table-auto border-separate border-spacing-0.5">
                            <thead>
                                <tr class="bg-gray-100 text-left text-gray-700">
                                    <th class="px-4 py-2 border-b font-semibold">ID</th>
                                    <th class="px-4 py-2 border-b font-semibold">Name</th>
                                    <th class="px-4 py-2 border-b font-semibold">Email</th>
                                    <th class="px-4 py-2 border-b font-semibold">Position</th>
                                    <th class="px-4 py-2 border-b font-semibold">Manager</th>
                                    <th class="px-4 py-2 border-b font-semibold text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($employees as $employee)

                            <!-- Row dữ liệu -->
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="border-b px-4 py-2 text-gray-700">{{$employee->id}}</td>
                                <td class="border-b px-4 py-2 text-gray-700">{{$employee->name}}</td>
                                <td class="border-b px-4 py-2 text-gray-700">{{$employee->email}}</td>
                                <td class="border-b px-4 py-2 text-gray-700">{{$employee->id_position}}</td>
                                <td class="border-b px-4 py-2 text-gray-700">{{$employee->id_manager}}</td>

                                <td class="border-b px-4 py-2">
                                    <div class="flex space-x-3 justify-center">
                                        <!-- Nút Chi Tiết (Màu xanh nước biển) -->
                                        <a href="" class="inline-flex items-center px-3 py-2 bg-teal-500 text-white rounded-full hover:bg-teal-400 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 21l-6-6M10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path>
                                            </svg>
                                        </a>

                                        <!-- Nút Sửa (Màu vàng) -->
                                        <a href="#"
                                           data-user='@json($employee)'
                                           class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path>
                                            </svg>
                                        </a>

                                        <!-- Nút Xóa (Màu đỏ) -->
                                        <form action="" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18M9 6V4a2 2 0 0 1 4 0v2h-4zM5 6l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12H5z"></path>
                                            </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                            @endforeach

                            </tbody>
                        </table>

                        <!-- Phân trang (Nếu cần) -->
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <!-- Dòng thông tin phân trang -->
                                Showing 1 to 10 of 50 results
                                </div>
                                <div>
                                    <!-- Liên kết phân trang -->
                                    <nav class="flex space-x-2">
                                        <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">Previous</a>
                                        <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">Next</a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // === CREATE USER MODAL ===
            const openUserModalButton  = document.getElementById('openUserModal');
            const userModal            = document.getElementById('userModal');
            const closeUserModalButton = document.getElementById('closeUserModal');

            openUserModalButton.addEventListener('click', e => {
                e.preventDefault();
                userModal.classList.remove('hidden');
            });

            closeUserModalButton.addEventListener('click', () => {
                userModal.classList.add('hidden');
            });

            // === EDIT USER MODAL ===
            const editModal            = document.getElementById('userEditModal');
            const closeEditModalButton = document.getElementById('closeEditModal');
            const editUserForm         = document.getElementById('editUserForm');

            document.querySelectorAll('.openEditModal').forEach(button => {
                button.addEventListener('click', e => {
                    e.preventDefault();
                    const user = JSON.parse(button.getAttribute('data-user'));

                    // Fill form with existing data
                    document.getElementById('editUserId').value    = user.id;
                    document.getElementById('editName').value      = user.name;
                    document.getElementById('editEmail').value     = user.email;
                    document.getElementById('editPosition').value  = user.position;

                    // Set the action URL (ví dụ: /users/{id})
                    editUserForm.action = `/users/${user.id}`;

                    editModal.classList.remove('hidden');
                });
            });

            closeEditModalButton.addEventListener('click', () => {
                editModal.classList.add('hidden');
            });

            // === CLICK OUTSIDE ĐỂ ĐÓNG CẢ HAI MODAL ===
            window.addEventListener('click', e => {
                if (e.target === userModal) {
                    userModal.classList.add('hidden');
                }
                if (e.target === editModal) {
                    editModal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
