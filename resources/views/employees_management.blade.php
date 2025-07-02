<x-app-layout>
    <x-slot name="header">
        <!-- Bao gồm Modal -->
        <x-user-model />
    </x-slot>
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-800 -mt-6 -mx-6 px-6 py-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Quản lý nhân viên</h1>
                    <p class="text-emerald-100 text-lg">Quản lý thông tin và hồ sơ nhân viên</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text" id="search"
                            class="pl-10 pr-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg focus:ring-2 focus:ring-white/50 focus:outline-none text-white placeholder-emerald-100 w-64"
                            placeholder="Tìm kiếm nhân viên...">
                        <span class="absolute left-3 top-2.5 text-emerald-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                        </span>
                    </div>
                    
                    <!-- Add Employee Button -->
                    <button id="openUserModal"
                        class="flex items-center gap-2 bg-white text-emerald-600 hover:bg-emerald-50 font-semibold px-6 py-3 rounded-xl shadow-lg transition-all text-base whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Thêm nhân viên
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
                    <div class="p-3 bg-emerald-100 rounded-xl">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Tổng nhân viên</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $employees->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Có thông tin chi tiết</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $employees->where('details', '!=', null)->count() }}</p>
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
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Đang hoạt động</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $employees->count() }}</p>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Employee List -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Danh sách nhân viên
                    </h3>
                    <span class="bg-emerald-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ $employees->count() }} nhân viên</span>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Responsive wrapper -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Ảnh Đại Diện</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tên Đầy Đủ</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Email</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($employees as $employee)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white'>{{ $employee->id }}</td>
                                    <td class='px-6 py-4 whitespace-nowrap'>
                                        @if($employee->avatar)
                                            <img src="/storage/{{ $employee->avatar }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 shadow-sm">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-sm">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white'>{{ $employee->name }}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300'>{{ $employee->email }}</td>
                                    <td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium'>
                                        <div class="flex items-center justify-center space-x-2">
                                            <button 
                                                data-user='@json($employee)' 
                                                class="openDetailModal inline-flex items-center px-3 py-2 {{ $employee->details ? 'bg-blue-500 hover:bg-teal-600' : 'bg-gray-400 cursor-not-allowed' }} text-white rounded-lg transition duration-200 shadow-sm" 
                                                title="{{ $employee->details ? 'Chi tiết' : 'Không có thông tin chi tiết' }}"
                                                {{ !$employee->details ? 'disabled' : '' }}
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <a href="javascript:void(0);" data-user='@json($employee)' class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200 shadow-sm" title="Sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="/users/{{ $employee->id }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa nhân viên này không?');">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 shadow-sm" title="Xóa">
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
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Hiển thị {{ $employees->firstItem() ?? 0 }} đến {{ $employees->lastItem() ?? 0 }} của {{ $employees->total() }} kết quả
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($employees->hasPages())
                            <div class="flex items-center space-x-2">
                                @if($employees->onFirstPage())
                                    <button disabled class="px-3 py-2 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ $employees->previousPageUrl() }}" class="px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </a>
                                @endif
                                
                                @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                                    <a href="{{ $url }}" 
                                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $page == $employees->currentPage() ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                                
                                @if($employees->hasMorePages())
                                    <a href="{{ $employees->nextPageUrl() }}" class="px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <button disabled class="px-3 py-2 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tìm kiếm nhân viên
        document.getElementById('search').addEventListener('input', function() {
            const searchText = this.value.toLowerCase().trim();
            document.querySelectorAll('tbody tr').forEach(row => {
                const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                row.style.display = (name.includes(searchText) || email.includes(searchText)) ? '' : 'none';
            });
            const visible = document.querySelectorAll('tbody tr:not([style*="display: none"])').length;
            const total = document.querySelectorAll('tbody tr').length;
            const info = document.querySelector('.text-sm.text-gray-700');
            if (info) info.textContent = `Hiển thị ${visible} của ${total} kết quả`;
        });

        // Mở modal chi tiết
        document.querySelectorAll('.openDetailModal').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const user = JSON.parse(this.getAttribute('data-user'));
                document.getElementById('detailName').textContent = user.name;
                document.getElementById('detailEmail').textContent = user.email;
                if (user.created_at) {
                    const [d, t] = user.created_at.split('T');
                    document.getElementById('detailCreatedDate').textContent = d;
                    document.getElementById('detailCreatedTime').textContent = t ? t.split('.')[0] : '';
                    document.getElementById('rowCreatedDate').style.display = '';
                    document.getElementById('rowCreatedTime').style.display = '';
                } else {
                    document.getElementById('rowCreatedDate').style.display = 'none';
                    document.getElementById('rowCreatedTime').style.display = 'none';
                }
                document.getElementById('detailAvatar').src = user.avatar ? `/storage/${user.avatar}` : 'https://via.placeholder.com/80';
                document.getElementById('userDetailModal').classList.remove('hidden');
            });
        });

        // Mở modal chỉnh sửa
        document.querySelectorAll('.openEditModal').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const user = JSON.parse(this.getAttribute('data-user'));
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editName').value = user.name;
                document.getElementById('editEmail').value = user.email;
                document.getElementById('editPassword').value = '';
                document.getElementById('editAvatar').value = '';
                document.getElementById('editUserForm').action = `/users/${user.id}`;
                document.getElementById('userEditModal').classList.remove('hidden');
            });
        });

        // Đóng modal
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.modal').classList.add('hidden');
            });
        });
    });
    </script>
</x-app-layout>
