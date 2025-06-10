<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Work Summary Management') }}
        </h2>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('work.search') }}" class="flex items-end space-x-4">
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700">Nhân viên</label>
                    <input type="text" name="user" id="user" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="ID hoặc tên">
                </div>

                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Tháng</label>
                    <select name="month" id="month" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Chọn tháng --</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Năm</label>
                    <select name="year" id="year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Chọn năm --</option>
                        @foreach ($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Ngày</label>
                    <input type="text" name="date" id="datepicker" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Chọn ngày">
                </div>

                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                        Tìm kiếm
                    </button>
                </div>
            </form>

            <!-- Thêm script Flatpickr -->
            <script>
                flatpickr("#datepicker", {
                    dateFormat: "Y-m-d",  // Định dạng ngày theo kiểu YYYY-MM-DD
                });
            </script>

            <!-- Nút xuất Excel -->
            <div class="mt-4">
                <form method="GET" action="{{ route('work.export') }}">
                    <!-- Các input ẩn để gửi các tham số tìm kiếm -->
                    <input type="hidden" name="user" value="{{ request('user') }}">
                    <input type="hidden" name="month" value="{{ request('month') }}">
                    <input type="hidden" name="year" value="{{ request('year') }}">

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700">
                        Xuất file Excel
                    </button>
                </form>
            </div>



            <!-- Bảng dữ liệu với text cứng -->
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Nhân viên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-center">Tổng giờ làm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-center">Giờ làm thêm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b text-center">Ngày nghỉ</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @if($workSummaries->isEmpty() )
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b text-center">Không có dữ liệu</td>
                        </tr>
                    @endif
                    @foreach ($workSummaries as $summary)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $summary->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $summary->user->name ?? '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $summary->month ?? '' }} / {{ $summary->year ?? ''  }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b text-center">{{ $summary->total_work_hours ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b text-center">{{ $summary->total_overtime_hours ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b text-center">{{ $summary->total_leave_days ?? 0 }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
