<x-app-layout>
    <x-slot name="header">
        <x-overtime-model />
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quản lý Yêu cầu Làm thêm giờ') }}
            </h2>
            <span>
                <button id="openCreateOvertimeModal"
                        class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-5 py-2 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tạo yêu cầu làm thêm giờ
                </button>
            </span>
        </div>
    </x-slot>

    <div class="py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        {{-- Danh sách ca làm + yêu cầu OT --}}
        <section class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-2xl font-semibold mb-8 text-gray-900 dark:text-gray-100">Danh sách Ca làm và Yêu cầu OT</h3>

            @php
                function formatDateTime($datetime) {
                    return \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i');
                }
            @endphp

            @forelse ($overtimeShifts as $shift)
                <div class="mb-6 border border-gray-300 dark:border-gray-700 rounded-lg p-4 hover:shadow transition-shadow duration-200 relative">
                    <div class="absolute top-4 right-4 flex space-x-2">
                        <!-- Nút Sửa -->
                        <button
                            type="button"
                            data-shift='@json($shift)'
                            class="openEditModal inline-flex items-center px-3 py-2 bg-yellow-500 rounded-full text-white hover:bg-yellow-400 transition"
                            title="Chỉnh sửa" aria-label="Chỉnh sửa ca làm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 17v5h5l11-11-5-5-11 11v5h-5z"></path>
                            </svg>
                        </button>

                        <!-- Nút Xóa -->
                        <form action="{{ route('overtime.destroy', $shift->id) }}" method="POST" class="inline-block"
                              onsubmit="return confirm('Bạn có chắc muốn xóa ca làm này không?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 bg-red-600 rounded-full text-white hover:bg-red-500 transition"
                                    title="Xóa" aria-label="Xóa ca làm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18M9 6V4a2 2 0 0 1 4 0v2h-4zM5 6l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12H5z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>



                    <h4 class="font-semibold text-lg mb-3 text-gray-800 dark:text-gray-200">Ca làm: {{ $shift->name }}</h4>
                    <p class="mb-1 text-sm"><strong>Mô tả:</strong> {{ $shift->description }}</p>
                    <p class="mb-1 text-sm"><strong>Thời gian:</strong> {{ ($shift->start_time) }} - {{ ($shift->end_time) }}</p>
                    <p class="mb-3 text-sm"><strong>Số lượng tối đa:</strong> {{ $shift->max_registrations }}</p>
                    <p class="mb-3 text-sm"><strong>Nhóm gồm:</strong> {{ $shift->overtimeRequests->where('status', 'approved')->pluck('user.name')->unique()->implode(', ') }}</p>

                    @if($shift->overtimeRequests->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có yêu cầu làm thêm giờ nào.</p>
                    @else
                        @if($shift->current_registrations < $shift->max_registrations)

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="flex items-center mb-3 px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 focus:outline-none">
                                    <span class="mr-2">
                                        Danh sách đăng ký đang chờ duyệt ({{ $shift->overtimeRequests->where('status', 'pending')->count() }})
                                    </span>
                                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }"
                                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition class="mt-2">
                                    <table class="w-full text-sm border-collapse border border-gray-300 dark:border-gray-700">
                                        <thead>
                                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Nhân viên</th>
                                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Thời gian tạo</th>
                                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-center">Trạng thái</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($shift->overtimeRequests->where('status', 'pending') as $request)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $request->user->name }}</td>
                                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                                    Vào lúc {{ \Carbon\Carbon::parse($request->created_at)->format('H:i') }} ngày {{ \Carbon\Carbon::parse($request->created_at)->format('d/m') }}
                                                </td>

                                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                                    <form method="POST" action="{{ route('overtimeRequests.update', $request->id) }}" class="inline-block mr-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved" />
                                                        <button type="submit"
                                                                class="px-3 py-1 rounded text-white text-sm
                                                {{ $request->status === 'approved' ? 'bg-green-600' : 'bg-green-400 hover:bg-green-500' }}">
                                                            Phê duyệt
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('overtimeRequests.update', $request->id) }}" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected" />
                                                        <button type="submit"
                                                                class="px-3 py-1 rounded text-white text-sm
                                                {{ $request->status === 'rejected' ? 'bg-red-600' : 'bg-red-400 hover:bg-red-500' }}">
                                                            Từ chối
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Đã đủ thành viên cho dự án</p>
                        @endif
                    @endif

                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có ca làm thêm giờ nào.</p>
            @endforelse
        </section>
    </div>
</x-app-layout>

