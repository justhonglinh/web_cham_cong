<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight">
            {{ __('📸 Chấm công bằng khuôn mặt') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8" style="background: linear-gradient(135deg, #e0e7ff 0%, #fdf2f8 100%); min-height: calc(100vh - 64px);">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Container -->
            <div class="bg-white/95 rounded-2xl shadow-2xl p-4 sm:p-6 lg:p-8 border border-blue-100 backdrop-blur-md relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-0 left-0 w-32 h-32 bg-blue-500 rounded-full -translate-x-16 -translate-y-16"></div>
                    <div class="absolute bottom-0 right-0 w-24 h-24 bg-purple-500 rounded-full translate-x-12 translate-y-12"></div>
                </div>

                <!-- Header -->
                <div class="relative z-10 text-center mb-6">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm font-semibold rounded-full mb-4 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Đưa khuôn mặt vào khung camera
                    </div>
                    
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Chấm công bằng khuôn mặt</h1>
                    <p class="text-gray-600 text-sm sm:text-base">Sử dụng camera để nhận diện và chấm công</p>
                </div>

                <!-- Shift Information -->
                @if($shiftInfo)
                    <div class="relative z-10 mb-6">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-green-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ca làm việc: {{ $shiftInfo['name'] }}
                                </h3>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    @if($shiftStatus === 'active') bg-green-100 text-green-800
                                    @elseif($shiftStatus === 'upcoming') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($shiftStatus === 'active') Đang hoạt động
                                    @elseif($shiftStatus === 'upcoming') Sắp tới
                                    @else Đã kết thúc @endif
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-xs text-gray-600 mb-1">Giờ bắt đầu</div>
                                    <div class="text-lg font-bold text-green-700">{{ $shiftInfo['start_time'] }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600 mb-1">Giờ hiện tại</div>
                                    <div class="text-lg font-bold text-blue-700">{{ $shiftInfo['current_time'] }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600 mb-1">Giờ kết thúc</div>
                                    <div class="text-lg font-bold text-red-700">{{ $shiftInfo['end_time'] }}</div>
                                </div>
                            </div>

                            @if($shiftStatus === 'active')
                                @if($canCheckIn)
                                    <div class="mt-3 p-2 bg-green-100 rounded-lg">
                                        <div class="flex items-center text-green-800 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @if(isset($shiftInfo['is_late']) && $shiftInfo['is_late'])
                                                ⚠️ Bạn đang đi muộn! Vui lòng chấm công ngay.
                                            @else
                                                ✅ Bạn có thể chấm công ngay bây giờ.
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3 p-2 bg-blue-100 rounded-lg">
                                        <div class="flex items-center text-blue-800 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Bạn đã chấm công hôm nay.
                                        </div>
                                    </div>
                                @endif
                            @elseif($shiftStatus === 'upcoming')
                                <div class="mt-3 p-2 bg-yellow-100 rounded-lg">
                                    <div class="flex items-center text-yellow-800 text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Ca làm việc chưa bắt đầu. Vui lòng quay lại sau.
                                    </div>
                                </div>
                            @else
                                <div class="mt-3 p-2 bg-gray-100 rounded-lg">
                                    <div class="flex items-center text-gray-800 text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Ca làm việc đã kết thúc.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="relative z-10 mb-6">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <div class="flex items-center text-red-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <span class="font-semibold">Bạn chưa được phân công ca làm việc</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Location Status -->
                <div id="locationStatus" class="relative z-10 bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4 text-center">
                    <div class="flex items-center justify-center text-blue-700 font-medium text-sm">
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Đang xác định vị trí...
                    </div>
                </div>

                <!-- Validation Status -->
                <div id="validationStatus" class="relative z-10 mb-4 hidden">
                    <div class="grid grid-cols-1 gap-3">
                        <!-- Face Validation -->
                        <div id="faceValidation" class="bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                            <div class="flex items-center text-yellow-700 font-medium text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span id="faceValidationText">Đang kiểm tra khuôn mặt...</span>
                            </div>
                        </div>

                        <!-- Location Validation -->
                        <div id="locationValidation" class="bg-blue-50 border border-blue-200 rounded-xl p-3">
                            <div class="flex items-center text-blue-700 font-medium text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span id="locationValidationText">Đang kiểm tra vị trí...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Container -->
                <div class="relative z-10 mb-6">
                    <div class="relative bg-gray-900 rounded-xl overflow-hidden shadow-lg">
                        <video id="video" autoplay playsinline class="w-full h-64 sm:h-80 object-cover"></video>
                        
                        <!-- Camera Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-48 h-48 sm:w-56 sm:h-56 border-2 border-white/50 rounded-full relative">
                                <div class="absolute inset-0 border-2 border-blue-400 rounded-full animate-pulse"></div>
                                <div class="absolute top-2 left-2 w-4 h-4 border-t-2 border-l-2 border-blue-400 rounded-tl-lg"></div>
                                <div class="absolute top-2 right-2 w-4 h-4 border-t-2 border-r-2 border-blue-400 rounded-tr-lg"></div>
                                <div class="absolute bottom-2 left-2 w-4 h-4 border-b-2 border-l-2 border-blue-400 rounded-bl-lg"></div>
                                <div class="absolute bottom-2 right-2 w-4 h-4 border-b-2 border-r-2 border-blue-400 rounded-br-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden Canvas -->
                <canvas id="canvas" style="display:none;"></canvas>

                <!-- Preview Container -->
                <div id="preview" class="relative z-10 mb-6 hidden">
                    <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg">
                        <img id="previewImage" class="w-full h-64 sm:h-80 object-cover" alt="Ảnh chụp">
                        <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                            ✓ Đã chụp
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="relative z-10 space-y-3">
                    @if($shiftStatus === 'active' && $canCheckIn)
                        <button id="captureBtn" 
                                class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            📸 Chụp ảnh & Chấm công
                        </button>

                        <!-- Submit Form -->
                        <form id="attendanceForm" method="POST" action="{{ route('employees.attendance.process') }}" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="hidden" name="image1" id="image1">
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <input type="hidden" name="distance" id="distance">
                            
                            <button type="submit" id="submitBtn" 
                                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                🚀 Gửi chấm công
                            </button>
                        </form>
                    @else
                        <div class="w-full bg-gray-100 text-gray-500 font-semibold py-3 px-6 rounded-xl text-center">
                            @if($shiftStatus === 'no_shift')
                                Chưa có ca làm việc
                            @elseif($shiftStatus === 'upcoming')
                                Ca làm việc chưa bắt đầu
                            @elseif($shiftStatus === 'ended')
                                Ca làm việc đã kết thúc
                            @else
                                Đã chấm công hôm nay
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Instructions -->
                <div class="relative z-10 mt-6 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-purple-800 mb-1">Quy trình chấm công:</h3>
                            <ul class="text-xs sm:text-sm text-purple-700 space-y-1">
                                <li>• <strong>Bước 1:</strong> Đảm bảo khuôn mặt rõ nét, không bị ngược sáng</li>
                                <li>• <strong>Bước 2:</strong> Không che khuất khuôn mặt bằng khẩu trang, mũ, kính</li>
                                <li>• <strong>Bước 3:</strong> Hệ thống sẽ so sánh với ảnh đại diện của bạn</li>
                                <li>• <strong>Bước 4:</strong> Kiểm tra vị trí hiện tại với phạm vi làm việc của quản lý</li>
                                <li>• <strong>Bước 5:</strong> Vui lòng bật GPS để xác định vị trí chính xác</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="relative z-10 mt-6 text-center">
                    <a href="{{ route('employees.dashboard') }}" 
                       class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Quay lại Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const captureBtn = document.getElementById('captureBtn');
            const submitBtn = document.getElementById('submitBtn');
            const attendanceForm = document.getElementById('attendanceForm');
            const preview = document.getElementById('preview');
            const previewImage = document.getElementById('previewImage');
            const image1Input = document.getElementById('image1');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const distanceInput = document.getElementById('distance');
            const locationStatus = document.getElementById('locationStatus');
            const faceValidationText = document.getElementById('faceValidationText');
            const locationValidationText = document.getElementById('locationValidationText');

            let stream = null;
            let capturedImage = null;

            // Khởi tạo camera
            async function initCamera() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            facingMode: 'user',
                            width: { ideal: 640 },
                            height: { ideal: 480 }
                        } 
                    });
                    video.srcObject = stream;
                } catch (err) {
                    console.error('Lỗi khi truy cập camera:', err);
                    locationStatus.innerHTML = `
                        <div class="flex items-center justify-center text-red-700 font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Không thể truy cập camera
                        </div>
                    `;
                }
            }

            // Lấy vị trí hiện tại
            function getCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            latitudeInput.value = lat;
                            longitudeInput.value = lng;
                            
                            // Tính khoảng cách đến công ty (tọa độ mẫu)
                            const officeLat = 21.028511;
                            const officeLng = 105.804817;
                            const distance = calculateDistance(lat, lng, officeLat, officeLng);
                            distanceInput.value = distance;
                            
                            locationStatus.innerHTML = `
                                <div class="flex items-center justify-center text-green-700 font-medium text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Đã xác định vị trí (${distance.toFixed(0)}m từ công ty)
                                </div>
                            `;
                        },
                        function(error) {
                            console.error('Lỗi khi lấy vị trí:', error);
                            locationStatus.innerHTML = `
                                <div class="flex items-center justify-center text-yellow-700 font-medium text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Không thể xác định vị trí
                                </div>
                            `;
                        }
                    );
                } else {
                    locationStatus.innerHTML = `
                        <div class="flex items-center justify-center text-yellow-700 font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Trình duyệt không hỗ trợ GPS
                        </div>
                    `;
                }
            }

            // Tính khoảng cách Haversine
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371; // Bán kính Trái Đất (km)
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                const distance = R * c; // km
                return distance * 1000; // m
            }

            // Chụp ảnh
            if (captureBtn) {
                captureBtn.addEventListener('click', function() {
                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    capturedImage = canvas.toDataURL('image/png');
                    image1Input.value = capturedImage;
                    
                    // Hiển thị preview
                    previewImage.src = capturedImage;
                    preview.classList.remove('hidden');
                    video.parentElement.classList.add('hidden');
                    
                    // Ẩn nút chụp, hiện form submit
                    captureBtn.classList.add('hidden');
                    attendanceForm.classList.remove('hidden');
                });
            }

            // Submit form
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    if (!capturedImage) {
                        alert('Vui lòng chụp ảnh trước khi chấm công');
                        return;
                    }
                    
                    // Hiển thị validation status
                    document.getElementById('validationStatus').classList.remove('hidden');
                    locationStatus.classList.add('hidden');
                    
                    // Cập nhật trạng thái validation
                    faceValidationText.textContent = 'Đang so sánh khuôn mặt với ảnh đại diện...';
                    locationValidationText.textContent = 'Đang kiểm tra vị trí với quản lý...';
                    
                    // Disable button và hiển thị loading
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Đang xử lý chấm công...
                    `;
                    
                    // Submit form
                    attendanceForm.submit();
                });
            }

            // Khởi tạo
            initCamera();
            getCurrentLocation();

            // Cleanup khi rời trang
            window.addEventListener('beforeunload', function() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            });
        });
    </script>
</x-app-layout>