<x-app-layout>
    <x-slot name="header">
    </x-slot>
    
    <x-success-model />
    <x-shift-model />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-600 via-amber-600 to-orange-800 -mt-6 -mx-6 px-6 py-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Quản lý ca làm</h1>
                    <p class="text-orange-100 text-lg">Quản lý lịch trình và thời gian làm việc</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text" id="search"
                            class="pl-10 pr-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg focus:ring-2 focus:ring-white/50 focus:outline-none text-white placeholder-orange-100 w-64"
                            placeholder="Tìm kiếm ca làm...">
                        <span class="absolute left-3 top-2.5 text-orange-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                        </span>
                    </div>
                    
                    <!-- View All Button -->
                    @if(isset($showAll) && $showAll)
                        <a href="{{ route('shifts.index') }}" 
                           class="flex items-center gap-2 bg-orange-500 text-white hover:bg-orange-600 font-semibold px-4 py-2 rounded-lg transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Xem ca làm hiện tại
                        </a>
                    @else
                        <a href="{{ route('shifts.all') }}" 
                           class="flex items-center gap-2 bg-white/20 text-white hover:bg-white/30 font-semibold px-4 py-2 rounded-lg transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Xem tất cả ca làm
                        </a>
                    @endif
                    
                    <!-- Add Shift Button -->
                    <button id="openCreateShiftModal"
                        class="flex items-center gap-2 bg-white text-orange-600 hover:bg-orange-50 font-semibold px-6 py-3 rounded-xl shadow-lg transition-all text-base whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Thêm ca làm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-xl">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Tổng ca làm</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Shift::where('user_id', Auth::id())->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-xl">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Đang hiển thị</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $shifts->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Được sử dụng</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $usedShiftsCount = 0;
                                foreach($shifts as $shiftItem) {
                                    if($shiftItem->attendances()->exists()) {
                                        $usedShiftsCount++;
                                    }
                                }
                                echo $usedShiftsCount;
                            @endphp
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-gray-100 rounded-xl">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Đã ẩn</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Shift::where('user_id', Auth::id())->count() - $shifts->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shift List -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @if(isset($showAll) && $showAll)
                            Danh sách tất cả ca làm
                        @else
                            Danh sách ca làm đang hoạt động
                        @endif
                    </h3>
                    <span class="bg-orange-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ $shifts->count() }} ca làm</span>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Thông báo về logic ẩn ca làm -->
                @if(!isset($showAll) || !$showAll)
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800">Thông tin về hiển thị ca làm</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Hệ thống tự động ẩn những ca làm việc đã được sử dụng cho các ngày trong quá khứ để giữ giao diện gọn gàng. 
                                Chỉ hiển thị ca làm đang hoạt động và chưa có lịch sử sử dụng cũ.
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800">Chế độ xem tất cả ca làm</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                Bạn đang xem tất cả ca làm, bao gồm cả những ca làm đã được sử dụng cho các ngày trong quá khứ. 
                                Những ca làm này thường được ẩn để giữ giao diện gọn gàng.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Responsive wrapper -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Ca Làm Việc</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Giờ bắt đầu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Giờ kết thúc</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Trạng thái</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($shifts as $shift)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-amber-500 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $shift->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $shift->start_time }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $shift->end_time }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $today = now()->toDateString();
                                            $hasOldAttendance = \App\Models\Attendance::where('shift_id', $shift->id)
                                                ->where('date', '<', $today)
                                                ->exists();
                                            $hasCurrentAttendance = \App\Models\Attendance::where('shift_id', $shift->id)
                                                ->where('date', '>=', $today)
                                                ->exists();
                                        @endphp
                                        
                                        @if($hasOldAttendance)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Ca làm cũ
                                            </span>
                                        @elseif($hasCurrentAttendance)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Đang sử dụng
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Chưa sử dụng
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            @php
                                                $today = now()->toDateString();
                                                $hasOldAttendance = \App\Models\Attendance::where('shift_id', $shift->id)
                                                    ->where('date', '<', $today)
                                                    ->exists();
                                                $hasCurrentAttendance = \App\Models\Attendance::where('shift_id', $shift->id)
                                                    ->where('date', '>=', $today)
                                                    ->exists();
                                            @endphp
                                            
                                            <!-- Nút Sửa -->
                                            <a href="javascript:void(0);" 
                                               data-user='@json($shift)'
                                               class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition duration-200 shadow-sm" 
                                               title="Sửa ca làm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            
                                            <!-- Nút Xóa -->
                                            <form action="/shift/management/{{ $shift->id }}" method="POST" class="inline-block" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" 
                                                    class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition duration-200 shadow-sm" 
                                                    title="Xóa ca làm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div id="table-info" class="text-sm text-gray-700 dark:text-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <span id="page-info" class="text-sm text-gray-500 dark:text-gray-400 mr-4"></span>
                        <button class="px-3 py-2 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed text-sm disabled:opacity-50" id="prevPage" onclick="changePage('prev')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button class="px-3 py-2 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed text-sm disabled:opacity-50" id="nextPage" onclick="changePage('next')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentPage = 1;
    const rowsPerPage = 10;
    const shifts = @json($shifts);
    let filteredShifts = shifts;

    function renderTable(data) {
        const start = (currentPage - 1) * rowsPerPage;
        const pageData = data.slice(start, start + rowsPerPage);
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = "";
        pageData.forEach(shift => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${shift.name}</td>
                <td>${shift.start_time}</td>
                <td>${shift.end_time}</td>
                <td class="text-center">Chưa sử dụng</td>
                <td class="text-center">
                    <a href="javascript:void(0);" data-user='${JSON.stringify(shift)}' class="openEditModal">Sửa</a>
                    <form action="/shift/management/${shift.id}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit">Xóa</button>
                    </form>
                </td>
            `;
            tbody.appendChild(row);
        });
        document.getElementById('table-info').textContent = `Hiển thị ${data.length ? (start + 1) : 0} đến ${Math.min(currentPage * rowsPerPage, data.length)} của ${data.length} kết quả`;
        document.getElementById('page-info').textContent = `Trang ${currentPage} của ${Math.ceil(data.length / rowsPerPage) || 1}`;
        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage * rowsPerPage >= data.length;
    }

    function changePage(direction) {
        if (direction === 'next' && currentPage * rowsPerPage < filteredShifts.length) currentPage++;
        else if (direction === 'prev' && currentPage > 1) currentPage--;
        renderTable(filteredShifts);
    }

    document.getElementById('search').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        filteredShifts = shifts.filter(s => [s.id, s.name, s.start_time, s.end_time].join(' ').toLowerCase().includes(q));
        currentPage = 1;
        renderTable(filteredShifts);
    });

    </script>
    
    <!-- Load model-shift.js to ensure edit button functionality -->
    <script src="{{ asset('js/model-shift.js') }}"></script>
</x-app-layout>
