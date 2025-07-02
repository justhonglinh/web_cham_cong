<x-app-layout>
    <x-slot name="header">
    </x-slot>
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-green-800 -mt-6 -mx-6 px-6 py-8 mb-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Quản lý yêu cầu nghỉ phép</h1>
                    <p class="text-green-100 text-lg">Phê duyệt và quản lý yêu cầu đi muộn/nghỉ phép/về sớm</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text" id="search"
                            class="pl-10 pr-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg focus:ring-2 focus:ring-white/50 focus:outline-none text-white placeholder-green-100 w-64"
                            placeholder="Tìm kiếm yêu cầu...">
                        <span class="absolute left-3 top-2.5 text-green-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                        </span>
                    </div>
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
                    <div class="p-3 bg-green-100 rounded-xl">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Tổng yêu cầu</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $leaveRequests->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-xl">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Chờ duyệt</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $leaveRequests->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Đã phê duyệt</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $leaveRequests->where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-xl">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Đã từ chối</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $leaveRequests->where('status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Requests List -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Danh sách yêu cầu
                    </h3>
                    <span class="bg-green-400 bg-opacity-30 px-3 py-1 rounded-full text-sm text-white">{{ $leaveRequests->count() }} yêu cầu</span>
                </div>
            </div>
            
            <div class="p-6">
                @if($leaveRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nhân viên
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Loại yêu cầu
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ngày yêu cầu
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Thời gian
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lý do
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($leaveRequests as $request)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($request->user->avatar)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $request->user->avatar) }}" alt="">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                                            <span class="text-white font-medium text-sm">{{ substr($request->user->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $request->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $request->user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($request->type === 'late') bg-yellow-100 text-yellow-800
                                                @elseif($request->type === 'leave') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ $request->type_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $request->request_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($request->start_time && $request->end_time)
                                                {{ \Carbon\Carbon::parse($request->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($request->end_time)->format('H:i') }}
                                            @else
                                                <span class="text-gray-500">Cả ngày</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-xs truncate" title="{{ $request->reason }}">
                                                {{ $request->reason }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($request->status === 'pending')
                                                <div class="flex space-x-2">
                                                    <button onclick="openApprovalModal({{ $request->id }}, 'approved')" 
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Phê duyệt
                                                    </button>
                                                    <button onclick="openApprovalModal({{ $request->id }}, 'rejected')" 
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Từ chối
                                                    </button>
                                                </div>
                                                @else
                                                <span class="inline-flex px-2 py-1 text-center font-semibold rounded-full 
                                                    @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($request->status === 'approved') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $request->status_text }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $leaveRequests->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có yêu cầu nào</h3>
                        <p class="mt-1 text-sm text-gray-500">Nhân viên chưa gửi yêu cầu nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal phê duyệt/từ chối -->
    <div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Phê duyệt yêu cầu</h3>
                <form id="approvalForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" id="statusInput">
                    
                    <div class="mb-4">
                        <label for="manager_note" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú (tùy chọn)</label>
                        <textarea id="manager_note" name="manager_note" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Nhập ghi chú nếu cần..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeApprovalModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                            Xác nhận
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openApprovalModal(requestId, status) {
        document.getElementById('approvalForm').action = `/leave-requests/${requestId}/status`;
        document.getElementById('statusInput').value = status;
        document.getElementById('modalTitle').textContent = status === 'approved' ? 'Phê duyệt yêu cầu' : 'Từ chối yêu cầu';
        document.getElementById('approvalModal').classList.remove('hidden');
    }
    function closeApprovalModal() {
        document.getElementById('approvalModal').classList.add('hidden');
    }
    document.getElementById('approvalModal').addEventListener('click', function(e) {
        if (e.target === this) closeApprovalModal();
    });
    document.getElementById('search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const name = row.querySelector('td:first-child .text-sm.font-medium')?.textContent.toLowerCase() || '';
            const email = row.querySelector('td:first-child .text-sm.text-gray-500')?.textContent.toLowerCase() || '';
            const type = row.querySelector('td:nth-child(2) span')?.textContent.toLowerCase() || '';
            const reason = row.querySelector('td:nth-child(5) div')?.textContent.toLowerCase() || '';
            row.style.display = (name.includes(searchTerm) || email.includes(searchTerm) || type.includes(searchTerm) || reason.includes(searchTerm)) ? '' : 'none';
        });
    });
    </script>
</x-app-layout> 