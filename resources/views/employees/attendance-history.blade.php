<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lịch sử chấm công
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Ngày</th>
                        <th class="px-4 py-2">Check In</th>
                        <th class="px-4 py-2">Check Out</th>
                        <th class="px-4 py-2">Tổng giờ</th>
                        <th class="px-4 py-2">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                        <tr>
                            <td class="px-4 py-2">{{ $attendance->date }}</td>
                            <td class="px-4 py-2">{{ $attendance->check_in }}</td>
                            <td class="px-4 py-2">{{ $attendance->check_out ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $attendance->total_hours ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @if($attendance->status == 'success')
                                    <span class="text-green-500">Thành công</span>
                                @elseif($attendance->status == 'fail')
                                    <span class="text-red-500">Thất bại</span>
                                @elseif($attendance->status == 'late')
                                    <span class="text-yellow-500">Muộn</span>
                                @elseif($attendance->status == 'overtime')
                                    <span class="text-blue-500">Overtime</span>
                                @else
                                    <span>{{ $attendance->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Phân trang -->
            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        </div>

        <!-- Nút quay lại Dashboard, nằm ngoài khung -->
        <div class="max-w-4xl mx-auto mt-4 flex justify-end">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                Quay lại Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
