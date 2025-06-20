<x-app-layout>
    <x-slot name="header">
        <!-- Bao gồm Modal -->
        <x-shift-model />
    </x-slot>
    
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-xl">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Tổng ca làm</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($shifts) }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Đang hoạt động</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($shifts) }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $shifts->filter(function($shift) { return $shift->attendances()->exists(); })->count() }}</p>
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
                        Danh sách ca làm
                    </h3>
                    <span class="bg-orange-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ count($shifts) }} ca làm</span>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Responsive wrapper -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Ca Làm Việc</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Giờ bắt đầu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Giờ kết thúc</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="javascript:void(0);" data-shift='{"id":{{ $shift->id }},"name":"{{ $shift->name }}","start_time":"{{ $shift->start_time }}","end_time":"{{ $shift->end_time }}"}' class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200 shadow-sm" title="Sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="/shift/management/{{ $shift->id }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" 
                                                    class="inline-flex items-center px-3 py-2 {{ $shift->attendances()->exists() ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600' }} text-white rounded-lg transition duration-200 shadow-sm" 
                                                    title="{{ $shift->attendances()->exists() ? 'Không thể xóa ca làm đang được sử dụng' : 'Xóa' }}"
                                                    {{ $shift->attendances()->exists() ? 'disabled' : '' }}>
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

        function bindShiftModalEvents() {
            document.querySelectorAll('.openEditModal').forEach(button => {
                button.onclick = function(e) {
                    e.preventDefault();
                    const shift = JSON.parse(this.getAttribute('data-shift'));
                    document.getElementById('editShiftId').value = shift.id;
                    document.getElementById('editShiftName').value = shift.name;
                    document.getElementById('editStartTime').value = shift.start_time;
                    document.getElementById('editEndTime').value = shift.end_time;
                    document.getElementById('editShiftForm').action = `/shift/management/${shift.id}`;
                    document.getElementById('editShiftModal').classList.remove('hidden');
                };
            });
        }

        function updateTableInfo(data) {
            const start = (data.length === 0) ? 0 : (currentPage - 1) * rowsPerPage + 1;
            const end = Math.min(currentPage * rowsPerPage, data.length);
            const total = data.length;
            document.getElementById('table-info').textContent = `Hiển thị ${start} đến ${end} của ${total} kết quả`;
        }

        function updatePageInfo(data) {
            const totalPages = Math.ceil(data.length / rowsPerPage) || 1;
            document.getElementById('page-info').textContent = `Trang ${currentPage} của ${totalPages}`;
        }

        function renderTable(data) {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const pageData = data.slice(start, end);
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = "";
            pageData.forEach(shift => {
                const row = document.createElement("tr");
                row.className = "hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200";
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-amber-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${shift.name}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-300">${shift.start_time}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-300">${shift.end_time}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="javascript:void(0);" data-shift='${JSON.stringify(shift)}' class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200 shadow-sm" title="Sửa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="/shift/management/${shift.id}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 {{ $shift->attendances()->exists() ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600' }} text-white rounded-lg transition duration-200 shadow-sm" 
                                    title="{{ $shift->attendances()->exists() ? 'Không thể xóa ca làm đang được sử dụng' : 'Xóa' }}"
                                    {{ $shift->attendances()->exists() ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            // Update pagination buttons
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage * rowsPerPage >= data.length;
            
            if (prevBtn.disabled) {
                prevBtn.className = "px-3 py-2 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed text-sm opacity-50";
            } else {
                prevBtn.className = "px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm";
            }
            
            if (nextBtn.disabled) {
                nextBtn.className = "px-3 py-2 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed text-sm opacity-50";
            } else {
                nextBtn.className = "px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm";
            }
            
            bindShiftModalEvents();
            updateTableInfo(data);
            updatePageInfo(data);
        }

        function changePage(direction) {
            if (direction === 'next' && currentPage * rowsPerPage < filteredShifts.length) {
                currentPage++;
            } else if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            }
            renderTable(filteredShifts);
        }

        document.getElementById('search').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            filteredShifts = shifts.filter(s => {
                let all = [
                    s.id,
                    s.name,
                    s.start_time,
                    s.end_time
                ].filter(Boolean).join(' ').toLowerCase();
                return all.includes(q);
            });
            currentPage = 1;
            renderTable(filteredShifts);
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Bind edit modal events
            document.querySelectorAll('.openEditModal').forEach(button => {
                button.onclick = function(e) {
                    e.preventDefault();
                    const shift = JSON.parse(this.getAttribute('data-shift'));
                    document.getElementById('editShiftId').value = shift.id;
                    document.getElementById('editShiftName').value = shift.name;
                    document.getElementById('editStartTime').value = shift.start_time;
                    document.getElementById('editEndTime').value = shift.end_time;
                    document.getElementById('editShiftForm').action = `/shift/management/${shift.id}`;
                    document.getElementById('editShiftModal').classList.remove('hidden');
                };
            });

            // Search functionality
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });

        renderTable(filteredShifts);
    </script>
</x-app-layout>
