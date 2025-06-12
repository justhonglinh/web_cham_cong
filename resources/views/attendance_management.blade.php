<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý chấm công') }}
        </h2>

        <!-- Nút chuyển bảng -->
        <div class="flex justify-center space-x-4 mt-6">
            <button id="show-attendance-table" class="px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Bảng chấm công
            </button>
            <button id="show-overtime-table" class="px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                Bảng tăng ca
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Bảng chấm công (Shift) -->
            <div id="attendance-table" class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg overflow-hidden transition-all duration-500">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                        Bảng chấm công nhân viên
                    </h1>

                    <div class="overflow-x-auto rounded-lg shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Nhân viên</th>
                                <th class="px-6 py-3 text-left">Ngày</th>
                                <th class="px-6 py-3 text-left">Ca làm</th>
                                <th class="px-6 py-3 text-left">Giờ vào</th>
                                <th class="px-6 py-3 text-left">Giờ ra</th>
                                <th class="px-6 py-3 text-left">Trạng thái</th>
                                <th class="px-6 py-3 text-left">Hoạt động</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($attendance_shifts as $att)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $att->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $att->shift->name }} ({{ $att->shift ? $att->shift->start_time . ' - ' . $att->shift->end_time : '—' }})</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $att->check_in_time ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $att->check_out_time ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                           <span class="inline-block px-2 py-1 rounded-full
                                                {{ $att->status === 'present'      ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $att->status === 'late'         ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $att->status === 'early_leave'  ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $att->status === 'absent'       ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $att->status === 'leave'       ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                           ">

                                                @if($att->status === 'present') Có mặt
                                               @elseif($att->status === 'late') Đi muộn
                                               @elseif($att->status === 'early_leave') Về sớm
                                               @elseif($att->status === 'absent') Vắng mặt
                                               @elseif($att->status === 'leave') Nghỉ phép
                                               @endif
                                           </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <form method="POST" action=" {{route('attendance.update', $att->id)}} ">
                                            @csrf
                                            @method('PATCH')
                                            <select name="shift_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm">
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
            </div>

            <!-- Bảng tăng ca -->
            <div id="overtime-table" class="{{ $attendance_overtimes->isEmpty() ? 'hidden' : '' }} bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg overflow-hidden transition-all duration-500">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                        Bảng tăng ca nhân viên
                    </h1>

                    <div class="overflow-x-auto rounded-lg shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Nhân viên</th>
                                <th class="px-6 py-3 text-left">Ngày</th>
                                <th class="px-6 py-3 text-left">Ca làm</th>
                                <th class="px-6 py-3 text-left">Giờ vào</th>
                                <th class="px-6 py-3 text-left">Giờ ra</th>
                                <th class="px-6 py-3 text-left">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($attendance_overtimes as $att)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $att->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $att->overtimeShift->name }} ({{ $att->overtimeShift ? $att->overtimeShift->start_time . ' - ' . $att->overtimeShift->end_time : '—' }})</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $att->check_in_time ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $att->check_out_time ?? '—' }}</td>
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
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Không có dữ liệu tăng ca
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>

<script>
    $(document).ready(function() {
        // Khởi tạo DataTables cho bảng chấm công
        $('#attendance-table table').DataTable();

        // Khởi tạo DataTables cho bảng tăng ca
        $('#overtime-table table').DataTable();
    });

    document.getElementById('show-attendance-table').addEventListener('click', function() {
        // Ẩn bảng tăng ca và hiển thị bảng chấm công
        document.getElementById('attendance-table').classList.remove('hidden');
        document.getElementById('overtime-table').classList.add('hidden');
    });

    document.getElementById('show-overtime-table').addEventListener('click', function() {
        // Ẩn bảng chấm công và hiển thị bảng tăng ca
        document.getElementById('attendance-table').classList.add('hidden');
        document.getElementById('overtime-table').classList.remove('hidden');
    });

    // Đảm bảo khi trang tải lại, bảng chấm công được hiển thị mặc định
    window.onload = function() {
        document.getElementById('attendance-table').classList.remove('hidden');
        document.getElementById('overtime-table').classList.add('hidden');
    };
</script>

