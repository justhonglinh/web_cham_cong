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
        {{-- Danh sách ca làm + yêu cầu OT --}}
        <section class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-2xl font-semibold mb-8 text-gray-900 dark:text-gray-100">Danh sách Ca Làm Thêm Giờ</h3>

            @php
                function formatDateTime($datetime) {
                    return \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i');
                }
            @endphp

            @forelse ($overtimeShifts as $shift)
                <div class="mb-6 border border-gray-300 dark:border-gray-700 rounded-lg p-4 hover:shadow transition-shadow duration-200 relative">
                    <h4 class="font-semibold text-lg mb-3 text-gray-800 dark:text-gray-200">Ca làm: {{ $shift->name }}</h4>
                    <p class="mb-1 text-sm"><strong>Mô tả:</strong> {{ $shift->description }}</p>
                    <p class="mb-1 text-sm"><strong>Thời gian:</strong> {{ formatDateTime($shift->start_time) }} - {{ formatDateTime($shift->end_time) }}</p>
                    <p class="mb-3 text-sm"><strong>Số lượng tối đa:</strong> {{ $shift->max_registrations }}</p>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm">Chưa có ca làm thêm giờ nào.</p>
            @endforelse
        </section>
    </div>
</x-app-layout>

