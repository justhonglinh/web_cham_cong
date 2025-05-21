<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <link rel="stylesheet" href="{{ asset('css/custom-datatable.css') }}">
            {{ __('Attendance Management') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <h1 class="text-2xl font-bold mb-6">Bảng chấm công nhân viên</h1>
                    <table id="myTable" class="min-w-full border border-gray-300 rounded">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 border">Nhân viên</th>
                            <th class="p-3 border">Ngày</th>
                            <th class="p-3 border">Ca làm</th>
                            <th class="p-3 border">Giờ vào</th>
                            <th class="p-3 border">Giờ ra</th>
                            <th class="p-3 border">Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($attendances as $att)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $att->user->name }}</td>
                                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                                <td class="border px-4 py-2">
                                    {{ $att->shift ? $att->shift->start_time . ' - ' . $att->shift->end_time : '—' }}
                                </td>
                                <td class="border px-4 py-2">{{ $att->check_in_time ?? '—' }}</td>
                                <td class="border px-4 py-2">{{ $att->check_out_time ?? '—' }}</td>
                                <td class="border px-4 py-2 capitalize">{{ $att->status ?? '—' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <script>
                        let table = new DataTable('#myTable');
                    </script>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
