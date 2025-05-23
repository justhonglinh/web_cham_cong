<x-app-layout>
    <x-slot name="header">
        <!-- Bao gồm Modal -->
        <x-shift-model />
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý ca làm và làm thêm giờ') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-16">

        {{-- Card quản lý Shift --}}
        <section class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <header class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Danh sách Ca làm</h3>
                <button id="openCreateShiftModal"
                        class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-5 py-2 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Thêm mới
                </button>
            </header>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 dark:border-gray-700 rounded-md"
                       id="shiftTable">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-left text-sm font-semibold uppercase tracking-wide">Tên ca</th>
                        <th class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-left text-sm font-semibold uppercase tracking-wide">Giờ bắt đầu</th>
                        <th class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-left text-sm font-semibold uppercase tracking-wide">Giờ kết thúc</th>
                        <th class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-center text-sm font-semibold uppercase tracking-wide">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($shifts as $shift)
                        <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="border border-gray-300 dark:border-gray-600 px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $shift->name }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $shift->start_time }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $shift->end_time }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-6 py-4 whitespace-nowrap text-center space-x-3">
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
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    <script>
        new DataTable('#shiftTable');
    </script>
</x-app-layout>
