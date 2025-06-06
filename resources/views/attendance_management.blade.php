<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý chấm công') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                        Quản lý chấm công và tăng ca
                    </h1>

                    <!-- Các nút để chuyển đổi bảng -->
                    <div class="flex mb-6 space-x-4">
                        <button onclick="toggleTable('attendance')" class="bg-teal-500 text-white px-6 py-3 rounded-md hover:bg-teal-400 transition duration-200 font-semibold text-lg shadow-md transform hover:scale-105">
                            Bảng Chấm Công
                        </button>
                        <button onclick="toggleTable('overtime')" class="bg-teal-500 text-white px-6 py-3 rounded-md hover:bg-teal-400 transition duration-200 font-semibold text-lg shadow-md transform hover:scale-105">
                            Bảng Tăng Ca
                        </button>
                    </div>

                    <!-- Bảng Chấm Công -->
                    <div id="attendance" class="attendance-table">
                        <div class="overflow-x-auto rounded-lg shadow-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nhân viên
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ngày
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ca làm
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Giờ vào
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Giờ ra
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Trạng thái
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hoạt động
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($attendance_shifts as $att)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $att->user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $att->shift->name }}
                                            ({{ $att->shift ? $att->shift->start_time . ' - ' . $att->shift->end_time : '—' }})
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $att->check_in_time ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $att->check_out_time ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                           <span class="inline-block px-2 py-1 rounded-full
                                                {{ $att->status === 'present'      ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $att->status === 'late'         ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $att->status === 'early_leave'  ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $att->status === 'absent'       ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $att->status === 'leave'       ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                           ">
                                                @if($att->status === 'present')
                                                   Có mặt
                                               @elseif($att->status === 'late')
                                                   Đi muộn
                                               @elseif($att->status === 'early_leave')
                                                   Về sớm
                                               @elseif($att->status === 'absent')
                                                   Vắng mặt
                                               @elseif($att->status === 'leave')
                                                   Nghỉ phép
                                               @endif
                                           </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <form method="POST" action=" {{route('attendance.update', $att->id)}} ">
                                                @csrf
                                                @method('PATCH')

                                                <select name="shift_id" onchange="this.form.submit()" class="rounded border-gray-300 text-sm">
                                                    <option value="">— Không có ca —</option>
                                                    @foreach($shifts as $shift)
                                                        <option value="{{ $shift->id }}" {{ $att->shift_id == $shift->id ? 'selected' : '' }}>
                                                            {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bảng Tăng Ca -->
                    <div id="overtime" class="overtime-table hidden">
                        <div class="overflow-x-auto rounded-lg shadow-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nhân viên
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ngày
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ca làm
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Giờ vào
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Giờ ra
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Trạng thái
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @if($attendance_overtimes->isEmpty())
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Không có dữ liệu tăng ca
                                        </td>
                                    </tr>
                                @endif
                                @foreach($attendance_overtimes as $att)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $att->user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $att->overtimeShift->name }}
                                            ({{ $att->overtimeShift ? $att->overtimeShift->start_time . ' - ' . $att->overtimeShift->end_time : '—' }})
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $att->check_in_time ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $att->check_out_time ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                          <span class="inline-block px-2 py-1 rounded-full
                                            {{ $att->status === 'present'   ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                            {{ $att->status === 'absent'    ? 'bg-red-100   text-red-800   dark:bg-red-900   dark:text-red-200' : '' }}
                                            {{ $att->status === 'late'      ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                            {{ !$att->status               ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                          ">
                                            {{ $att->status ? ucfirst($att->status) : '—' }}
                                          </span>
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
    </div>

    <script>
        function toggleTable(table) {
            // Ẩn tất cả các bảng
            document.querySelectorAll('.attendance-table, .overtime-table').forEach(function(table) {
                table.classList.add('hidden');
            });

            // Hiển thị bảng tương ứng
            document.getElementById(table).classList.remove('hidden');
        }
    </script>

</x-app-layout>
