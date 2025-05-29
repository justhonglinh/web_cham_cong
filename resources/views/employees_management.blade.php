<x-app-layout>
    <x-slot name="header">
        <!-- Bao gồm Modal -->
        <x-user-model />
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quản lý nhân viên') }}
            </h2>
            <span class="flex space-x-3">
                <button class="flex items-center bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <a href="#" id="openUserModal">Thêm nhân viên mới</a>
                </button>
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg overflow-hidden">
                <div class="p-6">

                    <!-- Responsive wrapper -->
                    <div class="overflow-x-auto">
                        <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Ảnh Đại Diện
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Tên Đầy Đủ
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Tên Quản Lý
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    Hành Động
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($employees as $employee)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors group">
                                    <!-- Avatar -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($employee->avatar)
                                            <img
                                                src="{{ asset('storage/' . $employee->avatar) }}"
                                                alt="Avatar of {{ $employee->name }}"
                                                class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-500 dark:ring-indigo-300"
                                            >
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">N/A</span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Name -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $employee->name }}
                                    </td>

                                    <!-- Email -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $employee->email }}
                                    </td>

                                    <!-- Manager -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $employee->manager ?? '—' }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <!-- Nút Chi Tiết (Màu xanh nước biển) -->
                                            <a href="javascript:void(0);"
                                               data-user='@json($employee)'
                                               class="openDetailModal inline-flex items-center px-3 py-2 bg-teal-500 text-white rounded-full hover:bg-teal-400 transition duration-200"
                                               title="View Details" aria-label="View Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 21l-6-6M10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path>
                                                </svg>
                                            </a>

                                            <!-- Nút Sửa (Màu vàng) -->
                                            <a href="javascript:void(0);"
                                               data-user='@json($employee)'
                                               class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400 transition duration-200"
                                               title="Edit User"
                                               aria-label="Edit User">
                                                <!-- Icon bút sửa -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path>
                                                </svg>
                                            </a>

                                            <!-- Nút Xóa (Màu đỏ) -->
                                            <form action="{{ route('users.destroy', $employee->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 transition duration-200" title="Delete User" aria-label="Delete User">
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
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        let table = new DataTable('#myTable');
    </script>
</x-app-layout>
