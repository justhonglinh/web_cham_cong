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
                        <div id="success-alert" class="text-green-600 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Nút mở modal thêm mới --}}
                    <button onclick="openAddModal()" class="mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Thêm mới
                    </button>

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
                                            <button onclick="openEditModal({{ $shiftItem->id }}, '{{ $shiftItem->name }}', '{{ $shiftItem->start_time }}', '{{ $shiftItem->end_time }}')" 
                                                class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-400"
                                                title="Sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 17v5h5l11-11-5-5L3 17z"></path>
                                                </svg>
                                            </button>

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

                    <!-- Modal Thêm/Sửa Ca Làm -->
                    <div id="shiftModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                            <button type="button" onclick="closeModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                            <h3 id="modalTitle" class="text-lg font-bold mb-4">Thêm mới ca làm</h3>
                            <form id="shiftForm" method="POST">
                                @csrf
                                <input type="hidden" name="_method" id="formMethod" value="POST">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <input type="text" name="name" id="shiftName" placeholder="Tên ca" required class="border rounded p-2 w-full">
                                    <input type="time" name="start_time" id="shiftStart" required class="border rounded p-2 w-full">
                                    <input type="time" name="end_time" id="shiftEnd" required class="border rounded p-2 w-full">
                                </div>
                                <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                                    Lưu
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#shiftTable');

        // Ẩn thông báo thành công sau 3 giây
        window.onload = function() {
            const alert = document.getElementById('success-alert');
            if(alert) {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);
            }
        };

        function openAddModal() {
            document.getElementById('modalTitle').innerText = 'Thêm mới ca làm';
            document.getElementById('shiftForm').action = "{{ route('shifts.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('shiftName').value = '';
            document.getElementById('shiftStart').value = '';
            document.getElementById('shiftEnd').value = '';
            document.getElementById('shiftModal').classList.remove('hidden');
        }

        function openEditModal(id, name, start, end) {
            document.getElementById('modalTitle').innerText = 'Chỉnh sửa ca làm';
            document.getElementById('shiftForm').action = "/shifts/" + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('shiftName').value = name;
            document.getElementById('shiftStart').value = start.substring(0,5);
            document.getElementById('shiftEnd').value = end.substring(0,5);
            document.getElementById('shiftModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('shiftModal').classList.add('hidden');
        }
    </script>
</x-app-layout>