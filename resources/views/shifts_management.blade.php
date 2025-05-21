<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="{{ asset('css/custom-datatable.css') }}">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý ca làm') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800">

                    {{-- Hiển thị thông báo thành công --}}
                    @if(session('success'))
                        <div class="text-green-600 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Form sửa hoặc thêm --}}
                    @if(isset($editMode) && $editMode)
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Chỉnh sửa ca làm</h3>
                    <form action="{{ route('shifts.update', $shift->id) }}" method="POST" class="space-y-4 mb-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" name="name" value="{{ $shift->name }}" required class="border rounded p-2 w-full" placeholder="Tên ca">
                            
                            {{-- Sửa ở đây để đảm bảo format giờ đúng --}}
                            <input type="time" name="start_time" value="{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}" required class="border rounded p-2 w-full">
                            <input type="time" name="end_time" value="{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}" required class="border rounded p-2 w-full">
                        </div>
                        <button type="submit" class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                            Cập nhật
                        </button>
                        <a href="{{ route('shifts.index') }}" class="ml-4 text-sm text-gray-500 hover:underline">Hủy</a>
                    </form>
                    @else
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Thêm mới ca làm</h3>
                        <form action="{{ route('shifts.store') }}" method="POST" class="space-y-4 mb-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <input type="text" name="name" placeholder="Tên ca" required class="border rounded p-2 w-full">
                                <input type="time" name="start_time" required class="border rounded p-2 w-full">
                                <input type="time" name="end_time" required class="border rounded p-2 w-full">
                            </div>
                            <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Thêm mới
                            </button>
                        </form>
                    @endif

                    {{-- Bảng danh sách --}}
                    <table class="min-w-full table-auto border-separate border-spacing-0.5" id="shiftTable">
                        <thead>
                            <tr class="bg-gray-100 text-left text-gray-700">
                                <th class="px-4 py-2 border-b font-semibold">Tên ca</th>
                                <th class="px-4 py-2 border-b font-semibold">Giờ bắt đầu</th>
                                <th class="px-4 py-2 border-b font-semibold">Giờ kết thúc</th>
                                <th class="px-4 py-2 border-b font-semibold text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shifts as $shiftItem)
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="border-b px-4 py-2">{{ $shiftItem->name }}</td>
                                    <td class="border-b px-4 py-2">{{ $shiftItem->start_time }}</td>
                                    <td class="border-b px-4 py-2">{{ $shiftItem->end_time }}</td>
                                    <td class="border-b px-4 py-2 text-center">
                                        <div class="flex justify-center space-x-3">
                                            {{-- Nút sửa --}}
                                            <a href="{{ route('shifts.edit', $shiftItem->id) }}"
                                               class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400"
                                               title="Sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 17v5h5l11-11-5-5L3 17z"></path>
                                                </svg>
                                            </a>

                                            {{-- Nút xóa --}}
                                            <form action="{{ route('shifts.destroy', $shiftItem->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ca này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-full hover:bg-red-500" title="Xóa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M9 6V4a2 2 0 0 1 4 0v2H9zm-4 0l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12H5z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#shiftTable');
    </script>
</x-app-layout>
