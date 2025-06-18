<x-app-layout>
    <x-slot name="header">
        <x-attendance-modal :shifts="$shifts" />
        <x-attendance-overtime-modal :shifts="$shifts" />
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nhân viên</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ca làm</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ vào</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Giờ ra</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                            @foreach($attendance_shifts as $att)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $att->user->avatar ? asset('storage/' . $att->user->avatar) : 'https://via.placeholder.com/40' }}" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $att->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $att->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $att->shift ? $att->shift->name : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('H:i') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $att->check_out_time ? \Carbon\Carbon::parse($att->check_out_time)->format('H:i') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'present' => 'text-green-600 dark:text-green-400',
                                                'absent' => 'text-red-600 dark:text-red-400',
                                                'leave' => 'text-yellow-600 dark:text-yellow-400',
                                                'late' => 'text-orange-600 dark:text-orange-400',
                                                'early_leave' => 'text-orange-600 dark:text-orange-400'
                                            ];
                                            $statusTexts = [
                                                'present' => 'Đã chấm công',
                                                'absent' => 'Vắng mặt',
                                                'leave' => 'Nghỉ phép',
                                                'late' => 'Đi muộn',
                                                'early_leave' => 'Về sớm'
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$att->status] ?? 'text-gray-600 dark:text-gray-400' }}">
                                            {{ $statusTexts[$att->status] ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button 
                                            data-attendance='@json($att)' 
                                            class="openDetailModal inline-flex items-center px-3 py-2 bg-teal-500 hover:bg-teal-400 text-white rounded-full transition duration-200" 
                                            title="Chi tiết"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-6-6M10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path></svg>
                                        </button>
                                        <button 
                                            data-attendance='{{ json_encode([
                                                "id" => $att->id,
                                                "user_id" => $att->user_id,
                                                "date" => $att->date,
                                                "shift_id" => $att->shift_id,
                                                "check_in_time" => $att->check_in_time,
                                                "check_out_time" => $att->check_out_time,
                                                "status" => $att->status,
                                            ]) }}'
                                            class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-400 text-white rounded-full transition duration-200" 
                                            title="Sửa"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path></svg>
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
                                        <a href="{{ $attendance_shifts->previousPageUrl() }}{{ strpos($attendance_shifts->previousPageUrl(), '?') !== false ? '&' : '?' }}table=attendance" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Trước
                                        </a>
                                    @endif

                                    @foreach($attendance_shifts->getUrlRange(1, $attendance_shifts->lastPage()) as $page => $url)
                                        <a href="{{ $url }}{{ strpos($url, '?') !== false ? '&' : '?' }}table=attendance" 
                                           class="px-3 py-1 rounded-md {{ $page == $attendance_shifts->currentPage() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                            {{ $page }}
                                        </a>
                                    @endforeach

                                    @if($attendance_shifts->hasMorePages())
                                        <a href="{{ $attendance_shifts->nextPageUrl() }}{{ strpos($attendance_shifts->nextPageUrl(), '?') !== false ? '&' : '?' }}table=attendance" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($attendance_overtimes as $att)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $att->user->avatar ? asset('storage/' . $att->user->avatar) : 'https://via.placeholder.com/40' }}" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $att->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $att->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $att->overtimeShift ? $att->overtimeShift->name : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('H:i') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $att->check_out_time ? \Carbon\Carbon::parse($att->check_out_time)->format('H:i') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'present' => 'text-green-600 dark:text-green-400',
                                                'absent' => 'text-red-600 dark:text-red-400',
                                                'leave' => 'text-yellow-600 dark:text-yellow-400',
                                                'late' => 'text-orange-600 dark:text-orange-400',
                                                'early_leave' => 'text-orange-600 dark:text-orange-400'
                                            ];
                                            $statusTexts = [
                                                'present' => 'Đã chấm công',
                                                'absent' => 'Vắng mặt',
                                                'leave' => 'Nghỉ phép',
                                                'late' => 'Đi muộn',
                                                'early_leave' => 'Về sớm'
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$att->status] ?? 'text-gray-600 dark:text-gray-400' }}">
                                            {{ $statusTexts[$att->status] ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button 
                                            class="openOvertimeDetailModal inline-flex items-center px-3 py-2 bg-teal-500 hover:bg-teal-400 text-white rounded-full transition duration-200" 
                                            data-overtime='@json($att)'
                                            title="Chi tiết"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-6-6M10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path></svg>
                                        </button>
                                        <button 
                                            class="openOvertimeEditModal inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-400 text-white rounded-full transition duration-200" 
                                            data-overtime='@json($att)'
                                            title="Sửa"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Không có dữ liệu tăng ca
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Phân trang cho bảng tăng ca -->
                    <div class="flex items-center justify-between mt-4">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Hiển thị {{ $attendance_overtimes->firstItem() ?? 0 }} đến {{ $attendance_overtimes->lastItem() ?? 0 }} của {{ $attendance_overtimes->total() }} kết quả
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($attendance_overtimes->hasPages())
                                <div class="flex items-center space-x-2">
                                    @if($attendance_overtimes->onFirstPage())
                                        <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                                            Trước
                                        </button>
                                    @else
                                        <a href="{{ $attendance_overtimes->previousPageUrl() }}{{ strpos($attendance_overtimes->previousPageUrl(), '?') !== false ? '&' : '?' }}table=overtime&tab=overtime" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Trước
                                        </a>
                                    @endif

                                    @foreach($attendance_overtimes->getUrlRange(1, $attendance_overtimes->lastPage()) as $page => $url)
                                        <a href="{{ $url }}{{ strpos($url, '?') !== false ? '&' : '?' }}table=overtime&tab=overtime" 
                                           class="px-3 py-1 rounded-md {{ $page == $attendance_overtimes->currentPage() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                            {{ $page }}
                                        </a>
                                    @endforeach

                                    @if($attendance_overtimes->hasMorePages())
                                        <a href="{{ $attendance_overtimes->nextPageUrl() }}{{ strpos($attendance_overtimes->nextPageUrl(), '?') !== false ? '&' : '?' }}table=overtime&tab=overtime" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
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
        </div>
    </div>

    @include('components.attendance-modal', [
        'attendance' => null,
        'users' => $users,
        'shifts' => $shifts
    ])

    @include('components.attendance-overtime-modal', [
        'shifts' => $shifts
    ])

    <script src="{{ asset('js/model-attendance.js') }}"></script>
    <script>
    // Tìm kiếm nhân viên
    document.getElementById('search').addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        const attendanceRows = document.querySelectorAll('#attendance-table tbody tr');
        const overtimeRows = document.querySelectorAll('#overtime-table tbody tr');
        
        // Tìm kiếm trong bảng chấm công
        attendanceRows.forEach(row => {
            const employeeName = row.querySelector('td:first-child').textContent.toLowerCase();
            if (employeeName.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Tìm kiếm trong bảng tăng ca
        overtimeRows.forEach(row => {
            const employeeName = row.querySelector('td:first-child').textContent.toLowerCase();
            if (employeeName.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Debug các nút đóng modal
    console.log("Close detail modal button:", document.getElementById('closeAttendanceOvertimeDetailModal'));
    console.log("Close edit modal button:", document.getElementById('closeAttendanceOvertimeEditModal'));
    console.log("Cancel edit modal button:", document.getElementById('cancelAttendanceOvertimeEditModal'));

    // Chuyển tab
    document.addEventListener('DOMContentLoaded', function() {
        var tabAttendance = document.getElementById('tab-attendance');
        var tabOvertime = document.getElementById('tab-overtime');
        var attendanceTable = document.getElementById('attendance-table');
        var overtimeTable = document.getElementById('overtime-table');
        var urlParams = new URLSearchParams(window.location.search);
        var tab = urlParams.get('tab');
        var table = urlParams.get('table');

        // Nếu đang ở tab overtime
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
            // Chuyển về trang 1 của bảng chấm công
            window.location.href = window.location.pathname + '?table=attendance&page=1';
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
            // Chuyển về trang 1 của bảng tăng ca
            window.location.href = window.location.pathname + '?table=overtime&tab=overtime&page=1';
        });
    });
    </script>
</x-app-layout>

