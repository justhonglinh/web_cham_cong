<x-app-layout>
    <x-slot name="header">
        <!-- Bao gồm Modal -->
        <x-shift-model />
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quản lý ca làm') }}
            </h2>
            <button id="openCreateShiftModal"
                    class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-5 py-2 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Thêm ca làm mới
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg overflow-hidden">
                <div class="p-6">

                    <!-- Responsive wrapper -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Ca Làm Việc
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Giờ bắt đầu
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Giờ kết thúc
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Hành động
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($shifts as $shift)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $shift->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $shift->start_time }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $shift->end_time }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="javascript:void(0);"
                                               data-shift='@json($shift)'
                                               class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 rounded-full text-white hover:bg-yellow-400 transition"
                                               title="Chỉnh sửa" aria-label="Chỉnh sửa ca làm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path>
                                                </svg>
                                            </a>

                                            <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');" >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-2 bg-red-600 rounded-full text-white hover:bg-red-500 transition"
                                                        title="Xóa" aria-label="Xóa ca làm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
</x-app-layout>
