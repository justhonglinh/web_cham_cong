<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quản lý Yêu cầu Làm thêm giờ') }}
            </h2>
            @if(Auth::user()->role === 'manager')
            <span>
                <a href="#" id="openCreateShiftModal"
                   class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-5 py-2 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tạo ca OT
                </a>
            </span>
            @endif
        </div>
    </x-slot>

    {{-- Modal tạo ca OT (chỉ cho manager) --}}
    @if(Auth::user()->role === 'manager')
    <div id="createOtModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 w-full max-w-md shadow-lg relative">
            <button id="closeCreateOtModal" class="absolute top-2 right-3 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h3 class="text-lg font-semibold mb-4">Tạo ca OT mới</h3>
            <form method="POST" action="{{ route('overtime.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block mb-1 font-medium">Tên ca:</label>
                    <input name="name" id="name" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="date" class="block mb-1 font-medium">Ngày:</label>
                    <input type="date" name="date" id="date" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4 flex gap-2">
                    <div class="flex-1">
                        <label for="start_time" class="block mb-1 font-medium">Bắt đầu:</label>
                        <input type="time" name="start_time" id="start_time" class="w-full border rounded p-2" required>
                    </div>
                    <div class="flex-1">
                        <label for="end_time" class="block mb-1 font-medium">Kết thúc:</label>
                        <input type="time" name="end_time" id="end_time" class="w-full border rounded p-2" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="max_registrations" class="block mb-1 font-medium">Số lượng tối đa:</label>
                    <input type="number" min="1" name="max_registrations" id="max_registrations" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block mb-1 font-medium">Mô tả:</label>
                    <textarea name="description" id="description" class="w-full border rounded p-2" rows="2"></textarea>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded font-semibold">Tạo ca OT</button>
            </form>
        </div>
    </div>
    @endif

    <div class="py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        {{-- Danh sách ca làm + xác nhận OT --}}
        <section class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-2xl font-semibold mb-8 text-gray-900 dark:text-gray-100">Danh sách Ca làm và Yêu cầu OT</h3>

            @php
                function formatDateTime($datetime) {
                    return \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i');
                }
                $user = Auth::user();
            @endphp

            @forelse ($overtimeShifts as $shift)
                <div class="mb-6 border border-gray-300 dark:border-gray-700 rounded-lg p-4 hover:shadow transition-shadow duration-200">
                    <h4 class="font-semibold text-lg mb-3 text-gray-800 dark:text-gray-200">Ca làm: {{ $shift->name }}</h4>
                    <p class="mb-1 text-sm"><strong>Mô tả:</strong> {{ $shift->description }}</p>
                    <p class="mb-1 text-sm"><strong>Thời gian:</strong> {{ formatDateTime($shift->start_time) }} - {{ formatDateTime($shift->end_time) }}</p>
                    <p class="mb-3 text-sm"><strong>Số lượng tối đa:</strong> {{ $shift->max_registrations }}</p>

                    @if($user->role === 'employee')
                        @php
                            $myRequest = $shift->overtimeRequests->where('user_id', $user->id)->first();
                        @endphp
                        <div class="mb-2">
                            @if(!$myRequest)
                                <form method="POST" action="{{ route('overtimeRequests.respond') }}">
                                    @csrf
                                    <input type="hidden" name="overtime_shift_id" value="{{ $shift->id }}">
                                    <button name="status" value="accepted" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded mr-2">Đồng ý</button>
                                    <button name="status" value="declined" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">Từ chối</button>
                                </form>
                            @else
                                <span class="inline-block px-3 py-1 rounded font-semibold
                                    {{ $myRequest->status === 'accepted' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $myRequest->status === 'accepted' ? 'Đã đồng ý' : 'Đã từ chối' }}
                                </span>
                            @endif
                        </div>
                    @endif

                    @if($user->role === 'manager')
                        <p class="mb-3 text-sm"><strong>Nhóm gồm:</strong>
                            {{ $shift->overtimeRequests->where('status', 'accepted')->pluck('user.name')->unique()->implode(', ') }}
                        </p>
                        <table class="w-full text-sm border-collapse border border-gray-300 dark:border-gray-700">
                            <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Nhân viên</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Thời gian xác nhận</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-2 text-center">Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($shift->overtimeRequests as $request)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $request->user->name }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 p-2">
                                            {{ \Carbon\Carbon::parse($request->created_at)->format('H:i d/m') }}
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                            @if($request->status === 'accepted')
                                                <span class="bg-green-500 text-white px-3 py-1 rounded">Đồng ý</span>
                                            @elseif($request->status === 'declined')
                                                <span class="bg-red-500 text-white px-3 py-1 rounded">Từ chối</span>
                                            @else
                                                <span class="bg-gray-400 text-white px-3 py-1 rounded">Chưa xác nhận</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có ca làm thêm giờ nào.</p>
            @endforelse
        </section>
    </div>

    <script>
        const openBtn = document.getElementById('openCreateShiftModal');
        const modal = document.getElementById('createOtModal');
        const closeBtn = document.getElementById('closeCreateOtModal');
        if(openBtn && modal && closeBtn){
            openBtn.onclick = function() { modal.classList.remove('hidden'); };
            closeBtn.onclick = function() { modal.classList.add('hidden'); };
            modal.onclick = function(e) { if (e.target === modal) modal.classList.add('hidden'); };
        }
    </script>
</x-app-layout>