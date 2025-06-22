<x-app-layout>
    <x-slot name="header">
        <x-overtime-model />
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bảng Thông Tin Làm Thêm Giờ') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        {{-- Thông báo lỗi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Danh sách ca làm thêm giờ --}}
        <section class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-2xl font-semibold mb-8 text-gray-900 dark:text-gray-100">Danh sách Ca Làm Thêm Giờ</h3>

            @php
                function formatDateTime($datetime) {
                    return \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i');
                }
            @endphp

            @forelse ($overtimeShifts as $shift)
                <div class="mb-6 border border-gray-300 dark:border-gray-700 rounded-lg p-4 hover:shadow transition-shadow duration-200 relative">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-lg mb-3 text-gray-800 dark:text-gray-200">Ca làm: {{ $shift->name }}</h4>
                            <p class="mb-1 text-sm"><strong>Mô tả:</strong> {{ $shift->description }}</p>
                            <p class="mb-1 text-sm"><strong>Ngày:</strong> {{ \Carbon\Carbon::parse($shift->date)->format('d/m/Y') }}</p>
                            <p class="mb-1 text-sm"><strong>Thời gian:</strong> {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</p>
                            <p class="mb-1 text-sm"><strong>Đã đăng ký:</strong> {{ $shift->current_registrations }}/{{ $shift->max_registrations }}</p>
                            
                            {{-- Hiển thị trạng thái đăng ký của nhân viên --}}
                            @php
                                $userRequest = $shift->overtimeRequests->where('user_id', auth()->id())->first();
                            @endphp
                            
                            @if($userRequest)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($userRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($userRequest->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $userRequest->status_text }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col space-y-2">
                            @if($shift->isFull && !$userRequest)
                                <span class="text-red-600 text-sm font-medium">Đã đầy</span>
                            @elseif($userRequest)
                                @if($userRequest->status === 'pending')
                                    <form action="{{ route('employees.overtime.unregister', $shift->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            Hủy đăng ký
                                        </button>
                                    </form>
                                @elseif($userRequest->status === 'approved')
                                    <span class="text-green-600 text-sm font-medium">Đã được phê duyệt</span>
                                @else
                                    <span class="text-red-600 text-sm font-medium">Đã bị từ chối</span>
                                @endif
                            @else
                                <form action="{{ route('employees.overtime.register', $shift->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Đăng ký
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có ca làm thêm giờ nào.</p>
            @endforelse
        </section>
    </div>
</x-app-layout>

