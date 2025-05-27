<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
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
