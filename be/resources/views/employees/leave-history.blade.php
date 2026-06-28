<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <!-- Header Section -->
    <div class="bg-gradient-to-br from-green-500 via-emerald-600 to-teal-700 -mt-6 -mx-6 px-6 py-12 mb-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-4xl font-bold mb-3">Lịch sử yêu cầu nghỉ phép</h1>
                    <p class="text-green-100 text-lg leading-relaxed">Theo dõi và quản lý yêu cầu đi muộn/nghỉ phép/về sớm của bạn</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('employees.leave.request') }}" class="group flex items-center gap-3 bg-white text-green-600 hover:bg-green-50 font-semibold px-8 py-4 rounded-2xl shadow-xl transition-all duration-300 text-base whitespace-nowrap transform hover:scale-105">
                        <svg class="w-6 h-6 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Đăng ký yêu cầu mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-4 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 mb-1">Tổng yêu cầu</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $leaveRequests->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-4 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-2xl">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 mb-1">Chờ duyệt</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $leaveRequests->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-4 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 mb-1">Đã phê duyệt</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $leaveRequests->where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-4 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 mb-1">Đã từ chối</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $leaveRequests->where('status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Requests List -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Lịch sử yêu cầu của bạn
                    </h3>
                    <span class="bg-white bg-opacity-20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium text-white">{{ $leaveRequests->count() }} yêu cầu</span>
                </div>
            </div>
            
            <div class="p-8">
                @if($leaveRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Loại yêu cầu
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Ngày yêu cầu
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Thời gian
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Lý do
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Trạng thái
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Ghi chú
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($leaveRequests as $request)
                                    <tr class="hover:bg-gray-50 transition-all duration-200">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full 
                                                @if($request->type === 'late') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800
                                                @elseif($request->type === 'leave') bg-gradient-to-r from-red-100 to-red-200 text-red-800
                                                @else bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 @endif">
                                                {{ $request->type_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $request->request_date->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $request->request_date->format('l') }}</div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-900">
                                            @if($request->start_time && $request->end_time)
                                                <div class="font-medium">{{ $request->start_time }} - {{ $request->end_time }}</div>
                                            @else
                                                <span class="text-gray-500 italic">Cả ngày</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 text-sm text-gray-900">
                                            <div class="max-w-xs truncate" title="{{ $request->reason }}">
                                                {{ $request->reason }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full 
                                                @if($request->status === 'pending') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800
                                                @elseif($request->status === 'approved') bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                                @else bg-gradient-to-r from-red-100 to-red-200 text-red-800 @endif">
                                                {{ $request->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-gray-900">
                                            @if($request->manager_note)
                                                <div class="max-w-xs truncate" title="{{ $request->manager_note }}">
                                                    {{ $request->manager_note }}
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-sm">
                                            @if($request->status === 'pending')
                                                <form method="POST" action="{{ route('employees.leave.destroy', $request->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50" 
                                                            onclick="return confirm('Bạn có chắc muốn xóa yêu cầu này?')">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $leaveRequests->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có yêu cầu nào</h3>
                        <p class="text-gray-500 mb-8">Bắt đầu tạo yêu cầu đầu tiên của bạn để quản lý thời gian làm việc.</p>
                        <a href="{{ route('employees.leave.request') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-lg text-base font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Đăng ký yêu cầu
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 