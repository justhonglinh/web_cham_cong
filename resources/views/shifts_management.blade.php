<x-app-layout>
    <x-slot name="header">
        <!-- Bao gồm Modal -->
        <x-shift-model />
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                    {{ __('Quản lý ca làm') }}
                </h2>
                <button id="openCreateShiftModal"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-full shadow transition-all text-base whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Thêm ca làm mới
                </button>
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
                        placeholder="Tìm kiếm ca làm...">
                </div>
            </div>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ca Làm Việc</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Giờ bắt đầu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Giờ kết thúc</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($shifts as $shift)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $shift->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $shift->start_time }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $shift->end_time }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="javascript:void(0);" data-shift='{"id":{{ $shift->id }},"name":"{{ $shift->name }}","start_time":"{{ $shift->start_time }}","end_time":"{{ $shift->end_time }}"}' class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400 transition duration-200" title="Sửa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path></svg>
                                                </a>
                                                <form action="/shift/management/{{ $shift->id }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 transition duration-200" title="Xóa">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M9 6V4a2 2 0 0 1 4 0v2h-4zM5 6l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12H5z"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Phân trang và info -->
                    <div class="flex items-center justify-between mt-4">
                        <div id="table-info" class="text-sm text-gray-600"></div>
                        <div class="flex items-center gap-4">
                            <span id="page-info" class="text-sm text-gray-500"></span>
                            <button class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="prevPage" onclick="changePage('prev')">Trước</button>
                            <button class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="nextPage" onclick="changePage('next')">Tiếp</button>
                        </div>
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
                row.innerHTML = `
                    <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100'>${shift.name}</td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300'>${shift.start_time}</td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300'>${shift.end_time}</td>
                    <td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>
                        <div class="flex items-center justify-center space-x-2">
                            <a href="javascript:void(0);" data-shift='${JSON.stringify(shift)}' class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400 transition duration-200" title="Sửa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path></svg>
                            </a>
                            <form action="/shift/management/${shift.id}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 transition duration-200" title="Xóa">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M9 6V4a2 2 0 0 1 4 0v2h-4zM5 6l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12H5z"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage * rowsPerPage >= data.length;
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
