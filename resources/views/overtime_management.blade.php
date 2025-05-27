<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quản lý Yêu cầu Làm thêm giờ') }}
            </h2>
            <span>
                <button id="openCreateShiftModal"
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
                <div class="mb-6 border border-gray-300 dark:border-gray-700 rounded-lg p-4 hover:shadow transition-shadow duration-200">
                    <h4 class="font-semibold text-lg mb-3 text-gray-800 dark:text-gray-200">Ca làm: {{ $shift->name }}</h4>
                    <p class="mb-1 text-sm"><strong>Mô tả:</strong> {{ $shift->description }}</p>
                    <p class="mb-1 text-sm"><strong>Thời gian:</strong> {{ formatDateTime($shift->start_time) }} - {{ formatDateTime($shift->end_time) }}</p>
                    <p class="mb-3 text-sm"><strong>Số lượng tối đa:</strong> {{ $shift->max_registrations }}</p>
                    <p class="mb-3 text-sm"><strong>Nhóm gồm:</strong> {{ $shift->overtimeRequests->where('status', 'approved')->pluck('user.name')->unique()->implode(', ') }}</p>

                @if($shift->overtimeRequests->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có yêu cầu làm thêm giờ nào.</p>
                @else
                    @if($shift->current_registrations < $shift->max_registrations)

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
                                                    Approved
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('overtimeRequests.update', $request->id) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected" />
                                                <button type="submit"
                                                        class="px-3 py-1 rounded text-white text-sm
                                                        {{ $request->status === 'rejected' ? 'bg-red-600' : 'bg-red-400 hover:bg-red-500' }}">
                                                    Rejected
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

