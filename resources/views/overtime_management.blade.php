<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/custom-datatable.css') }}">
        <script src="{{ asset('js/model-employee.js') }}"></script>

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Employees Management') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <!-- Bảng dữ liệu -->
                    <table class="min-w-full table-auto border-separate border-spacing-0.5" id="myTable">
                        <thead>
                        <tr class="bg-gray-100 text-left text-gray-700">
                            <th class="px-4 py-2 border-b font-semibold">Tên</th>
                            <th class="px-4 py-2 border-b font-semibold">Tăng Ca</th>
                            <th class="px-4 py-2 border-b font-semibold">Thời Gian</th>
                            <th class="px-4 py-2 border-b font-semibold">Lý Do</th>
                            <th class="px-4 py-2 border-b font-semibold">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($overtimeRequests as $overtimeRequest)

                            <!-- Row dữ liệu -->
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="border-b px-4 py-2 text-gray-700">{{ $overtimeRequest->user->name }}</td>
                                <td class="border-b px-4 py-2 text-gray-700">{{ $overtimeRequest->overtimeShift->name }}</td>
                                <!-- Thêm các cột khác nếu muốn -->
                                <td class="border-b px-4 py-2 text-gray-700">{{ $overtimeRequest->date }}</td>
                                <td class="border-b px-4 py-2 text-gray-700">{{ $overtimeRequest->requested_hour }}</td>
                                <td class="border-b px-4 py-2 text-gray-700">{{ $overtimeRequest->status }}</td>

                                <td class="border-b px-4 py-2">
                                    <div class="flex space-x-3 justify-center">
                                        <!-- Nút Chi Tiết (Màu xanh nước biển) -->
{{--                                        <a href="javascript:void(0);"--}}
{{--                                           data-user='@json($employee)'--}}
{{--                                           class="openDetailModal inline-flex items-center px-3 py-2 bg-teal-500 text-white rounded-full hover:bg-teal-400 transition duration-200"--}}
{{--                                           title="View Details" aria-label="View Details">--}}
{{--                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                                                <path d="M21 21l-6-6M10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path>--}}
{{--                                            </svg>--}}
{{--                                        </a>--}}


                                        <!-- Nút Sửa (Màu vàng) -->
{{--                                        <a href="javascript:void(0);"--}}
{{--                                           data-user='@json($employee)'--}}
{{--                                           class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400 transition duration-200"--}}
{{--                                           title="Edit User"--}}
{{--                                           aria-label="Edit User">--}}
{{--                                            <!-- Icon bút sửa -->--}}
{{--                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                                                <path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path>--}}
{{--                                            </svg>--}}
{{--                                        </a>--}}

                                        <!-- Nút Xóa (Màu đỏ) -->
{{--                                        <form action="{{ route('users.destroy', $employee->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa không?');">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 transition duration-200" title="Delete User" aria-label="Delete User">--}}
{{--                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                                                    <path d="M3 6h18M9 6V4a2 2 0 0 1 4 0v2h-4zM5 6l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12H5z"></path>--}}
{{--                                                </svg>--}}
{{--                                            </button>--}}
{{--                                        </form>--}}

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

{{--    <script>--}}
{{--        let table = new DataTable('#myTable');--}}
{{--    </script>--}}
</x-app-layout>
