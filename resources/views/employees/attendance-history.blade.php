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
            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</x-app-layout>