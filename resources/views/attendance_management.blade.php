<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                    {{ __('Quản lý chấm công') }}
                </h2>
            </div>
            <div class="flex items-center w-full md:w-1/2">
                <div class="relative w-full">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </span>
                    <input type="text" id="search"
                        class="pl-10 pr-4 py-2 border border-blue-300 bg-white rounded-full focus:ring-2 focus:ring-blue-200 focus:outline-none text-sm w-full shadow-sm transition placeholder-gray-400"
                        placeholder="Tìm kiếm nhân viên...">
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Tab chuyển bảng -->
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button id="tab-attendance" class="tab-btn text-blue-600 border-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none" type="button">
                        Bảng chấm công
                    </button>
                    <button id="tab-overtime" class="tab-btn text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm focus:outline-none" type="button">
                        Bảng tăng ca
                    </button>
                </nav>
            </div>
            <!-- Bảng chấm công -->
            <div id="attendance-table" class="block bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg overflow-hidden transition-all duration-500">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nhân viên</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ngày</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ca làm</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Giờ vào</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Giờ ra</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Trạng thái</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Thay đổi ca</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Chi tiết</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Sửa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($attendance_shifts as $att)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $att->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $att->shift->name }} </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $att->check_in_time ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $att->check_out_time ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $att->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                               ($att->status === 'absent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                               'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                            {{ $att->status === 'present' ? 'Đã chấm công' : 
                                               ($att->status === 'absent' ? 'Vắng mặt' : 'Đi muộn') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <form method="POST" action="{{ route('attendance.update', $att->id) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <select name="shift_id" onchange="this.form.submit()" 
                                                class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                <option value="">— Không có ca —</option>
                                                @foreach($shifts as $shift)
                                                    <option value="{{ $shift->id }}" {{ $att->shift_id == $shift->id ? 'selected' : '' }}>
                                                        {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $att->status }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="openAttendanceModal({{ json_encode($att) }})" 
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                            Chi tiết
                                        </button>
                                        <button onclick="openAttendanceModal({{ json_encode($att) }})" 
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            Sửa
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Phân trang cho bảng chấm công -->
                    <div class="flex items-center justify-between mt-4">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Hiển thị {{ $attendance_shifts->firstItem() ?? 0 }} đến {{ $attendance_shifts->lastItem() ?? 0 }} của {{ $attendance_shifts->total() }} kết quả
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($attendance_shifts->hasPages())
                                <div class="flex items-center space-x-2">
                                    @if($attendance_shifts->onFirstPage())
                                        <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                                            Trước
                                        </button>
                                    @else
                                        <a href="{{ $attendance_shifts->previousPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Trước
                                        </a>
                                    @endif

                                    @foreach($attendance_shifts->getUrlRange(1, $attendance_shifts->lastPage()) as $page => $url)
                                        <a href="{{ $url }}" 
                                           class="px-3 py-1 rounded-md {{ $page == $attendance_shifts->currentPage() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                            {{ $page }}
                                        </a>
                                    @endforeach

                                    @if($attendance_shifts->hasMorePages())
                                        <a href="{{ $attendance_shifts->nextPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Tiếp
                                        </a>
                                    @else
                                        <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                                            Tiếp
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng tăng ca -->
            <div id="overtime-table" class="hidden bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg overflow-hidden transition-all duration-500 mt-6">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nhân viên</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ngày</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ca làm</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Giờ vào</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Giờ ra</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($attendance_overtimes as $att)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $att->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $att->overtimeShift->name }} ({{ $att->overtimeShift ? $att->overtimeShift->start_time . ' - ' . $att->overtimeShift->end_time : '—' }})</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $att->check_in_time ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $att->check_out_time ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $att->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                               ($att->status === 'absent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                               'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
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
                    <!-- Phân trang cho bảng tăng ca -->
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Hiển thị {{ $attendance_overtimes->firstItem() ?? 0 }} đến {{ $attendance_overtimes->lastItem() ?? 0 }} trong tổng số {{ $attendance_overtimes->total() }} kết quả
                            </div>
                            <div class="flex space-x-2">
                                @if($attendance_overtimes->onFirstPage())
                                    <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed dark:bg-gray-700 dark:text-gray-400">
                                        Trước
                                    </button>
                                @else
                                    <a href="{{ $attendance_overtimes->previousPageUrl() }}{{ strpos($attendance_overtimes->previousPageUrl(), '?') !== false ? '&' : '?' }}tab=overtime" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                        Trước
                                    </a>
                                @endif
                                @for($i = 1; $i <= $attendance_overtimes->lastPage(); $i++)
                                    @if($i == $attendance_overtimes->currentPage())
                                        <span class="px-3 py-1 bg-blue-500 text-white rounded-md">{{ $i }}</span>
                                    @else
                                        <a href="{{ $attendance_overtimes->url($i) }}{{ strpos($attendance_overtimes->url($i), '?') !== false ? '&' : '?' }}tab=overtime" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                            {{ $i }}
                                        </a>
                                    @endif
                                @endfor
                                @if($attendance_overtimes->hasMorePages())
                                    <a href="{{ $attendance_overtimes->nextPageUrl() }}{{ strpos($attendance_overtimes->nextPageUrl(), '?') !== false ? '&' : '?' }}tab=overtime" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                        Tiếp
                                    </a>
                                @else
                                    <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed dark:bg-gray-700 dark:text-gray-400">
                                        Tiếp
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.attendance-modal', [
        'attendance' => null,
        'users' => $users,
        'shifts' => $shifts
    ])

    <script>
    // Tìm kiếm nhân viên
    document.getElementById('search').addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#attendance-table tbody tr');
        rows.forEach(row => {
            const employeeName = row.querySelector('td:first-child').textContent.toLowerCase();
            if (employeeName.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    // Chuyển tab
    document.addEventListener('DOMContentLoaded', function() {
        var tabAttendance = document.getElementById('tab-attendance');
        var tabOvertime = document.getElementById('tab-overtime');
        var attendanceTable = document.getElementById('attendance-table');
        var overtimeTable = document.getElementById('overtime-table');
        var urlParams = new URLSearchParams(window.location.search);
        var tab = urlParams.get('tab');
        if (tab === 'overtime') {
            attendanceTable.classList.remove('block');
            attendanceTable.classList.add('hidden');
            overtimeTable.classList.remove('hidden');
            overtimeTable.classList.add('block');
            tabOvertime.classList.add('text-blue-600', 'border-blue-600');
            tabOvertime.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
            tabAttendance.classList.remove('text-blue-600', 'border-blue-600');
            tabAttendance.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
        }
        tabAttendance.addEventListener('click', function() {
            attendanceTable.classList.remove('hidden');
            attendanceTable.classList.add('block');
            overtimeTable.classList.remove('block');
            overtimeTable.classList.add('hidden');
            tabAttendance.classList.add('text-blue-600', 'border-blue-600');
            tabAttendance.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
            tabOvertime.classList.remove('text-blue-600', 'border-blue-600');
            tabOvertime.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
            history.replaceState(null, '', window.location.pathname);
        });
        tabOvertime.addEventListener('click', function() {
            attendanceTable.classList.remove('block');
            attendanceTable.classList.add('hidden');
            overtimeTable.classList.remove('hidden');
            overtimeTable.classList.add('block');
            tabOvertime.classList.add('text-blue-600', 'border-blue-600');
            tabOvertime.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
            tabAttendance.classList.remove('text-blue-600', 'border-blue-600');
            tabAttendance.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
            history.replaceState(null, '', window.location.pathname + '?tab=overtime');
        });
    });
    </script>
</x-app-layout>

