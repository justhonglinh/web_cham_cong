<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

{{--     Mẫu trước --}}
    <div class="py-8 max-w-5xl mx-auto space-y-8">
        {{-- Form tạo yêu cầu OT --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4">Tạo yêu cầu cần người làm thêm giờ</h3>

            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium">Lý do làm thêm</label>
                    <textarea name="reason" rows="3" required
                              class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium">Thời gian bắt đầu</label>
                    <input type="datetime-local" name="start_time" required
                           class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium">Thời gian kết thúc</label>
                    <input type="datetime-local" name="end_time" required
                           class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium">Số lượng người cần</label>
                    <input type="number" name="quantity" min="1" required
                           class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Tạo yêu cầu</button>
            </form>
        </div>

        {{-- Danh sách yêu cầu OT (demo dữ liệu cứng) --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4">Danh sách yêu cầu OT</h3>

            @php
                // Dữ liệu giả lập các yêu cầu OT với start_time và end_time
                $overtimeRequests = [
                    [
                        'id' => 1,
                        'reason' => 'Dự án A gấp cần thêm người',
                        'start_time' => '2025-06-01 18:00',
                        'end_time' => '2025-06-01 21:00',
                        'quantity' => 3,
                    ],
                    [
                        'id' => 2,
                        'reason' => 'Báo cáo quý cần hoàn thiện',
                        'start_time' => '2025-06-03 19:00',
                        'end_time' => '2025-06-03 22:00',
                        'quantity' => 2,
                    ],
                ];

                // Dữ liệu giả lập các nhân viên apply theo yêu cầu OT
                $overtimeApplies = [
                    ['id' => 1, 'request_id' => 1, 'employee_name' => 'Nguyễn Văn A', 'status' => 'pending'],
                    ['id' => 2, 'request_id' => 1, 'employee_name' => 'Trần Thị B', 'status' => 'approved'],
                    ['id' => 3, 'request_id' => 2, 'employee_name' => 'Lê Văn C', 'status' => 'rejected'],
                ];

                // Hàm format ngày giờ cho hiển thị đẹp
                function formatDateTime($datetime) {
                    return \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i');
                }
            @endphp

            @foreach ($overtimeRequests as $request)
                <div class="mb-8 border border-gray-300 dark:border-gray-700 rounded p-4">
                    <h4 class="font-semibold">Yêu cầu #{{ $request['id'] }}</h4>
                    <p><strong>Lý do:</strong> {{ $request['reason'] }}</p>
                    <p>
                        <strong>Thời gian:</strong>
                        Từ {{ formatDateTime($request['start_time']) }} đến {{ formatDateTime($request['end_time']) }}
                    </p>
                    <p><strong>Số lượng cần:</strong> {{ $request['quantity'] }}</p>

                    {{-- Bảng danh sách nhân viên apply --}}
                    <table class="w-full mt-4 text-sm border-collapse border border-gray-300 dark:border-gray-700">
                        <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Nhân viên</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-left">Trạng thái</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($overtimeApplies as $apply)
                            @if ($apply['request_id'] === $request['id'])
                                <tr>
                                    <td class="border border-gray-300 dark:border-gray-600 p-2">
                                        {{ $apply['employee_name'] }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 capitalize">
                                        {{ $apply['status'] }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                        <select
                                            class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1"
                                            onchange="alert('Cập nhật trạng thái của {{ $apply['employee_name'] }} thành: ' + this.value)">
                                            <option value="pending" @if ($apply['status'] === 'pending') selected @endif>
                                                Pending
                                            </option>
                                            <option value="approved" @if ($apply['status'] === 'approved') selected @endif>
                                                Approved
                                            </option>
                                            <option value="rejected" @if ($apply['status'] === 'rejected') selected @endif>
                                                Rejected
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach

        </div>
    </div>


    {{-- Mẫu sau --}}
    {{-- Lấy tọa độ và địa chỉ hiện tại --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-lg font-semibold mb-4">Lấy tọa độ và địa chỉ hiện tại</h1>

                <button id="getLocationBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Lấy vị trí hiện tại
                </button>

                <p id="locationResult" class="mt-4 text-gray-700 dark:text-gray-300"></p>
                <p id="addressResult" class="mt-2 text-gray-600 dark:text-gray-400 italic"></p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('getLocationBtn').addEventListener('click', () => {
            const locationResult = document.getElementById('locationResult');
            const addressResult = document.getElementById('addressResult');
            addressResult.textContent = ''; // reset địa chỉ trước khi lấy mới

            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        locationResult.textContent = `Latitude: ${latitude}, Longitude: ${longitude}`;

                        // Gọi API Nominatim để lấy địa chỉ
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    addressResult.textContent = `Địa chỉ: ${data.display_name}`;
                                } else {
                                    addressResult.textContent = 'Không tìm thấy địa chỉ.';
                                }
                            })
                            .catch(error => {
                                addressResult.textContent = 'Lỗi khi lấy địa chỉ.';
                                console.error(error);
                            });
                    },
                    (error) => {
                        locationResult.textContent = `Lỗi lấy vị trí: ${error.message}`;
                    }
                );
            } else {
                locationResult.textContent = "Trình duyệt không hỗ trợ Geolocation.";
            }
        });
    </script>
</x-app-layout>
