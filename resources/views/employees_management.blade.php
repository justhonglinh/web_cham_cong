<x-app-layout>
    <x-slot name="header">
        <!-- Bao gồm Modal -->
        <x-user-model />
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                    {{ __('Quản lý nhân viên') }}
                </h2>
                <button id="openUserModal"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-full shadow transition-all text-base whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Thêm nhân viên mới
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
                        placeholder="Tìm kiếm nhân viên...">
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ảnh Đại Diện</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tên Đầy Đủ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($employees as $employee)
                                    <tr>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            @if($employee->avatar)
                                                <img src="/storage/{{ $employee->avatar }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center"><span class="text-gray-500 text-sm">N/A</span></div>
                                            @endif
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{{ $employee->name }}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>{{ $employee->email }}</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>
                                            <div class="flex items-center justify-center space-x-2">
                                                <button data-user='${JSON.stringify(employee)}' class="openDetailModal inline-flex items-center px-3 py-2 bg-teal-500 text-white rounded-full hover:bg-teal-400 transition duration-200" title="Chi tiết" ${!employee.details ? 'disabled style=\"opacity:0.5;cursor:not-allowed;\"' : ''}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-6-6M10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path></svg>
                                                </button>
                                                <a href="javascript:void(0);" data-user='${JSON.stringify(employee)}' class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400 transition duration-200" title="Sửa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path></svg>
                                                </a>
                                                <form action="/users/${employee.id}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa không?');">
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
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Hiển thị {{ $employees->firstItem() ?? 0 }} đến {{ $employees->lastItem() ?? 0 }} của {{ $employees->total() }} kết quả
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($employees->hasPages())
                                <div class="flex items-center space-x-2">
                                    @if($employees->onFirstPage())
                                        <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                                            Trước
                                        </button>
                                    @else
                                        <a href="{{ $employees->previousPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Trước
                                        </a>
                                    @endif
                                    @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                                        <a href="{{ $url }}" 
                                           class="px-3 py-1 rounded-md {{ $page == $employees->currentPage() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                            {{ $page }}
                                        </a>
                                    @endforeach
                                    @if($employees->hasMorePages())
                                        <a href="{{ $employees->nextPageUrl() }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
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

    <script>
        let currentPage = 1;
        const rowsPerPage = 10;
        const employees = @json($employees);
        let filteredEmployees = employees;

        function bindEmployeeModalEvents() {
            document.querySelectorAll('.openDetailModal').forEach(button => {
                button.onclick = function(e) {
                    e.preventDefault();
                    const user = JSON.parse(this.getAttribute('data-user'));
                    document.getElementById('detailName').textContent = user.name || '';
                    document.getElementById('detailEmail').textContent = user.email || '';
                    if (user.created_at) {
                        const [datePart, timeWithZone] = user.created_at.split('T');
                        const timePart = timeWithZone ? timeWithZone.split('.')[0] : '';
                        document.getElementById('detailCreatedDate').textContent = datePart || '';
                        document.getElementById('detailCreatedTime').textContent = timePart || '';
                        document.getElementById('rowCreatedDate').style.display = '';
                        document.getElementById('rowCreatedTime').style.display = '';
                    } else {
                        document.getElementById('detailCreatedDate').textContent = '';
                        document.getElementById('detailCreatedTime').textContent = '';
                        document.getElementById('rowCreatedDate').style.display = 'none';
                        document.getElementById('rowCreatedTime').style.display = 'none';
                    }
                    document.getElementById('detailAvatar').src = user.avatar ? `/storage/${user.avatar}` : 'https://via.placeholder.com/80';
                    // Phone
                    if (user.details && user.details.phone) {
                        document.getElementById('detailPhone').textContent = user.details.phone;
                        document.getElementById('rowPhone').style.display = '';
                    } else {
                        document.getElementById('detailPhone').textContent = '';
                        document.getElementById('rowPhone').style.display = 'none';
                    }
                    // Address
                    if (user.details && user.details.address) {
                        document.getElementById('detailAddress').textContent = user.details.address;
                        document.getElementById('rowAddress').style.display = '';
                    } else {
                        document.getElementById('detailAddress').textContent = '';
                        document.getElementById('rowAddress').style.display = 'none';
                    }
                    // Birthday
                    if (user.details && user.details.birthday) {
                        document.getElementById('detailBirthday').textContent = user.details.birthday;
                        document.getElementById('rowBirthday').style.display = '';
                    } else {
                        document.getElementById('detailBirthday').textContent = '';
                        document.getElementById('rowBirthday').style.display = 'none';
                    }
                    // Emergency Contact
                    if (user.details && user.details.emergency_contact) {
                        document.getElementById('detailEmergencyContact').textContent = user.details.emergency_contact;
                        document.getElementById('rowEmergencyContact').style.display = '';
                    } else {
                        document.getElementById('detailEmergencyContact').textContent = '';
                        document.getElementById('rowEmergencyContact').style.display = 'none';
                    }
                    document.getElementById('userDetailModal').classList.remove('hidden');
                };
            });
            document.querySelectorAll('.openEditModal').forEach(button => {
                button.onclick = function(e) {
                    e.preventDefault();
                    const user = JSON.parse(this.getAttribute('data-user'));
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editName').value = user.name;
                    document.getElementById('editEmail').value = user.email;
                    document.getElementById('editPassword').value = '';
                    document.getElementById('editAvatar').value = '';
                    document.getElementById('editUserForm').action = `/users/${user.id}`;
                    document.getElementById('userEditModal').classList.remove('hidden');
                };
            });
        }

        function updateTableInfo(data) {
            const start = (data.length === 0) ? 0 : (currentPage - 1) * rowsPerPage + 1;
            const end = Math.min(currentPage * rowsPerPage, data.length);
            const total = data.length;
            document.getElementById('table-info').textContent = `Showing ${start} to ${end} of ${total} entries`;
        }

        function updatePageInfo(data) {
            const totalPages = Math.ceil(data.length / rowsPerPage) || 1;
            document.getElementById('page-info').textContent = `Page ${currentPage} of ${totalPages}`;
        }

        function renderTable(data) {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const pageData = data.slice(start, end);
            const tbody = document.getElementById("employee-table-body");
            tbody.innerHTML = "";
            pageData.forEach(employee => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td class='px-6 py-4 whitespace-nowrap'>
                        ${employee.avatar ? `<img src="/storage/${employee.avatar}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">` : '<div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center"><span class="text-gray-500 text-sm">N/A</span></div>'}
                    </td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>${employee.name}</td>
                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>${employee.email}</td>
                    <td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>
                        <div class="flex items-center justify-center space-x-2">
                            <button data-user='${JSON.stringify(employee)}' class="openDetailModal inline-flex items-center px-3 py-2 bg-teal-500 text-white rounded-full hover:bg-teal-400 transition duration-200" title="Chi tiết" ${!employee.details ? 'disabled style=\"opacity:0.5;cursor:not-allowed;\"' : ''}>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-6-6M10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path></svg>
                            </button>
                            <a href="javascript:void(0);" data-user='${JSON.stringify(employee)}' class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400 transition duration-200" title="Sửa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path></svg>
                            </a>
                            <form action="/users/${employee.id}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa không?');">
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
            bindEmployeeModalEvents();
            updateTableInfo(data);
            updatePageInfo(data);
        }

        function changePage(direction) {
            if (direction === 'next' && currentPage * rowsPerPage < filteredEmployees.length) {
                currentPage++;
            } else if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            }
            renderTable(filteredEmployees);
        }

        document.getElementById('search').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            filteredEmployees = employees.filter(e => {
                let all = [
                    e.id,
                    e.name,
                    e.email,
                    e.avatar,
                    e.created_at,
                    e.manager && e.manager.name,
                    e.details && e.details.phone,
                    e.details && e.details.address,
                    e.details && e.details.birthday,
                    e.details && e.details.emergency_contact
                ].filter(Boolean).join(' ').toLowerCase();
                return all.includes(q);
            });
            currentPage = 1;
            renderTable(filteredEmployees);
        });

        renderTable(filteredEmployees);
    </script>
</x-app-layout>
