<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight">
            {{ __('📸 Chấm công bằng khuôn mặt') }}
        </h2>
    </x-slot>

    <!-- Meta tags cho mobile optimization -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

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

                <!-- Current Shift Information (chỉ hiển thị nếu trong vòng 1h trước ca) -->
                @if($shiftInfo && ($shiftStatus === 'active' || $shiftStatus === 'upcoming' || $shiftStatus === 'early_checkin'))
                    <div class="relative z-10 mb-6">
                        <div class="bg-gradient-to-r {{ $shiftStatus === 'active' ? 'from-green-50 to-emerald-50 border-green-200' : ($shiftStatus === 'early_checkin' ? 'from-yellow-50 to-orange-50 border-yellow-200' : 'from-blue-50 to-indigo-50 border-blue-200') }} border rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold {{ $shiftStatus === 'active' ? 'text-green-800' : ($shiftStatus === 'early_checkin' ? 'text-yellow-800' : 'text-blue-800') }} flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ca làm việc: {{ $shiftInfo['name'] }}
                                </h3>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $shiftStatus === 'active' ? 'bg-green-100 text-green-800' : ($shiftStatus === 'early_checkin' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $shiftStatus === 'active' ? 'Đang hoạt động' : ($shiftStatus === 'early_checkin' ? 'Có thể chấm công sớm' : 'Sắp bắt đầu') }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-xs text-gray-600 mb-1">Giờ bắt đầu</div>
                                    <div class="text-lg font-bold {{ $shiftStatus === 'active' ? 'text-green-700' : ($shiftStatus === 'early_checkin' ? 'text-yellow-700' : 'text-blue-700') }}">{{ $shiftInfo['start_time'] }}</div>
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

                            @if(($shiftStatus === 'active' || $shiftStatus === 'early_checkin') && $canCheckIn)
                                <div class="mt-3 p-2 {{ $shiftStatus === 'early_checkin' ? 'bg-yellow-100' : 'bg-green-100' }} rounded-lg">
                                    <div class="flex items-center {{ $shiftStatus === 'early_checkin' ? 'text-yellow-800' : 'text-green-800' }} text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @if($shiftStatus === 'early_checkin')
                                            ⏰ Bạn có thể chấm công sớm!
                                        @elseif(isset($shiftInfo['is_late']) && $shiftInfo['is_late'])
                                            ⚠️ Bạn đang đi muộn! Vui lòng chấm công ngay.
                                        @else
                                            ✅ Bạn có thể chấm công ngay bây giờ.
                                        @endif
                                    </div>
                                </div>
                            @elseif(($shiftStatus === 'active' || $shiftStatus === 'early_checkin') && $canCheckOut)
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $shiftEnd = isset($shiftInfo['end_time']) ? \Carbon\Carbon::createFromFormat('H:i', $shiftInfo['end_time']) : null;
                                    $canShowCheckout = $shiftEnd && $now->greaterThanOrEqualTo($shiftEnd);
                                @endphp
                                @if($canShowCheckout)
                                    <div class="mt-3 p-2 bg-red-100 rounded-lg">
                                        <div class="flex items-center text-red-800 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            ✅ Bạn có thể chấm công ra ngay bây giờ.
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3 p-2 bg-gray-100 rounded-lg">
                                        <div class="flex items-center text-gray-600 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            ⏰ Chưa đến giờ kết thúc ca.
                                        </div>
                                    </div>
                                @endif
                            @elseif($shiftStatus === 'upcoming')
                                <div class="mt-3 p-2 bg-blue-100 rounded-lg">
                                    <div class="flex items-center text-blue-800 text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ⏰ Ca làm việc sẽ bắt đầu lúc {{ $shiftInfo['start_time'] }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Permission Status -->
                <div id="permissionStatus" class="relative z-10 mb-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
                        <div class="text-center">
                            <div class="flex items-center justify-center text-blue-700 font-medium text-sm mb-2">
                                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Đang kiểm tra quyền truy cập...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Container -->
                <div class="relative z-10 mb-6">
                    <div class="relative bg-gray-900 rounded-xl overflow-hidden shadow-lg">
                        <!-- Camera Status Overlay -->
                        <div id="cameraStatus" class="absolute top-2 left-2 z-10 bg-black/50 text-white px-2 py-1 rounded text-xs">
                            <span id="cameraStatusText">Đang khởi tạo...</span>
                        </div>
                        
                        <video id="video" autoplay playsinline muted class="w-full h-64 sm:h-80 object-cover"></video>
                        
                        <!-- Camera Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-48 h-48 sm:w-56 sm:h-56 border-2 border-white/50 rounded-full relative">
                                <div class="absolute inset-0 border-2 border-blue-400 rounded-full animate-pulse"></div>
                                <div class="absolute top-2 left-2 w-4 h-4 border-t-2 border-l-2 border-blue-400 rounded-tl-lg"></div>
                                <div class="absolute top-2 right-2 w-4 h-4 border-t-2 border-r-2 border-blue-400 rounded-tr-lg"></div>
                                <div class="absolute bottom-2 left-2 w-4 h-4 border-b-2 border-l-2 border-blue-400 rounded-bl-lg"></div>
                                <div class="absolute bottom-2 right-2 w-4 h-4 border-b-2 border-r-2 border-blue-400 rounded-br-lg"></div>
                            </div>
                        </div>
                        
                        <!-- Mobile Instructions -->
                        <div class="absolute bottom-2 left-2 right-2 bg-black/50 text-white text-xs p-2 rounded text-center">
                            📱 Đảm bảo khuôn mặt nằm trong khung tròn
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
                        
                        <!-- Location Info Overlay -->
                        <div id="locationInfo" class="absolute bottom-0 left-0 right-0 bg-black/70 text-white p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Vị trí hiện tại:</span>
                                </div>
                                <div id="locationStatus" class="text-xs">
                                    <span id="locationText">Đang xác định...</span>
                                </div>
                            </div>
                            <div id="locationDetails" class="text-xs text-gray-300 mt-1 hidden">
                                <div>Kinh độ: <span id="longitudeText">-</span></div>
                                <div>Vĩ độ: <span id="latitudeText">-</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="relative z-10 space-y-3">
                    @if(($shiftStatus === 'active' || $shiftStatus === 'early_checkin') && $canCheckIn)
                        <!-- Nút chấm công vào -->
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-1 shadow-lg">
                            <button id="captureBtn" 
                                    class="w-full bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-semibold py-4 px-6 rounded-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold">📸 Chụp ảnh & Chấm công vào</span>
                            </button>
                        </div>
                        <form id="attendanceForm" method="POST" action="{{ route('employees.attendance.process') }}" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="hidden" name="image1" id="image1">
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <input type="hidden" name="action" value="check_in">
                            @if(isset($todayAttendance->shift_id) && $todayAttendance->shift_id)
                                <input type="hidden" name="shift_id" value="{{ $todayAttendance->shift_id }}">
                            @endif
                            @if(isset($todayAttendance->overtime_id) && $todayAttendance->overtime_id)
                                <input type="hidden" name="overtime_id" value="{{ $todayAttendance->overtime_id }}">
                            @endif
                            <button type="submit" id="submitBtn" 
                                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                🚀 Gửi chấm công
                            </button>
                        </form>
                    @elseif(($shiftStatus === 'active' || $shiftStatus === 'early_checkin') && $canCheckOut)
                        @php
                            $now = \Carbon\Carbon::now();
                            $shiftEnd = isset($shiftInfo['end_time']) ? \Carbon\Carbon::createFromFormat('H:i', $shiftInfo['end_time']) : null;
                            $canShowCheckout = $shiftEnd && $now->greaterThanOrEqualTo($shiftEnd);
                        @endphp
                        @if($canShowCheckout)
                            <!-- Nút chấm công ra -->
                            <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-xl p-1 shadow-lg">
                                <button id="captureBtn" 
                                        class="w-full bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-semibold py-4 px-6 rounded-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="font-semibold">📸 Chụp ảnh & Chấm công ra</span>
                                </button>
                            </div>

                            <!-- Submit Form for Check-out -->
                            <form id="attendanceForm" method="POST" action="{{ route('employees.attendance.process') }}" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="hidden" name="image1" id="image1">
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <input type="hidden" name="distance" id="distance">
                                <input type="hidden" name="action" value="check_out">
                                
                                <button type="submit" id="submitBtn" 
                                        class="w-full bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    🏠 Hoàn thành chấm công
                                </button>
                            </form>
                        @else
                            <div class="w-full bg-gray-100 text-gray-500 font-semibold py-3 px-6 rounded-xl text-center">
                                Chưa đến giờ kết thúc ca
                            </div>
                        @endif
                    @elseif($shiftStatus === 'upcoming')
                        <div class="w-full bg-gray-100 text-gray-500 font-semibold py-3 px-6 rounded-xl text-center">
                            Ca làm việc chưa bắt đầu
                        </div>
                    @elseif($shiftStatus === 'ended')
                        @php
                            $hasCheckedIn = isset($shiftInfo['check_in_time']) && $shiftInfo['check_in_time'];
                            $hasCheckedOut = isset($shiftInfo['check_out_time']) && $shiftInfo['check_out_time'];
                        @endphp
                        @if($hasCheckedIn && $hasCheckedOut)
                            <div class="w-full bg-green-100 text-green-700 font-semibold py-3 px-6 rounded-xl text-center">
                                ✅ Bạn đã chấm công đầy đủ
                            </div>
                        @else
                            <div class="w-full bg-gray-100 text-gray-500 font-semibold py-3 px-6 rounded-xl text-center">
                                Ca làm việc đã kết thúc
                            </div>
                        @endif
                    @else
                        @php
                            $hasCheckedIn = isset($shiftInfo['check_in_time']) && $shiftInfo['check_in_time'];
                            $hasCheckedOut = isset($shiftInfo['check_out_time']) && $shiftInfo['check_out_time'];
                        @endphp
                        @if($hasCheckedIn && $hasCheckedOut)
                            <div class="w-full bg-green-100 text-green-700 font-semibold py-3 px-6 rounded-xl text-center">
                                ✅ Bạn đã chấm công đầy đủ
                            </div>
                        @else
                            <div class="w-full bg-gray-100 text-gray-500 font-semibold py-3 px-6 rounded-xl text-center">
                                Chưa đến giờ chấm công
                            </div>
                        @endif
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
                            <h3 class="text-sm font-semibold text-purple-800 mb-2">Hướng dẫn chấm công:</h3>
                            <ul class="text-xs sm:text-sm text-purple-700 space-y-1">
                                <li class="flex items-start">
                                    <span class="text-purple-600 mr-2">1.</span>
                                    <span>Đảm bảo khuôn mặt rõ nét, không bị ngược sáng</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-purple-600 mr-2">2.</span>
                                    <span>Không che khuất khuôn mặt bằng khẩu trang, mũ, kính</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-purple-600 mr-2">3.</span>
                                    <span>Vui lòng bật GPS để xác định vị trí chính xác</span>
                                </li>
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
            function isMobileDevice() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }
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
            const permissionStatus = document.getElementById('permissionStatus');
            const cameraStatusText = document.getElementById('cameraStatusText');
            
            // Location display elements
            const locationText = document.getElementById('locationText');
            const locationDetails = document.getElementById('locationDetails');
            const longitudeText = document.getElementById('longitudeText');
            const latitudeText = document.getElementById('latitudeText');

            let stream = null;
            let capturedImage = null;
            let cameraApproved = false;

            // Cập nhật trạng thái quyền truy cập
            function updatePermissionStatus() {
                if (permissionStatus) {
                    if (cameraApproved) {
                        permissionStatus.innerHTML = `
                            <div class="bg-green-50 border border-green-200 rounded-xl p-3">
                                <div class="text-center">
                                    <div class="flex items-center justify-center text-green-700 font-medium text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Camera sẵn sàng! ✅
                                    </div>
                                    <div class="text-xs text-green-600 mt-1">
                                        Vị trí sẽ được xác định khi chụp ảnh
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        permissionStatus.innerHTML = `
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                                <div class="text-center">
                                    <div class="flex items-center justify-center text-yellow-700 font-medium text-sm mb-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Cần cấp quyền camera
                                    </div>
                                    <div class="text-xs text-yellow-600">
                                        Vui lòng cho phép quyền truy cập camera khi được hỏi
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }
            }

            // Khởi tạo camera
            async function initCamera() {
                try {
                    updateCameraStatus('Đang khởi tạo...');
                    
                    // Cấu hình camera tối ưu cho mobile
                    const constraints = {
                        video: { 
                            facingMode: 'user',
                            width: { 
                                ideal: isMobileDevice() ? 1280 : 640,
                                min: 320,
                                max: 1920
                            },
                            height: { 
                                ideal: isMobileDevice() ? 720 : 480,
                                min: 240,
                                max: 1080
                            }
                        }
                    };

                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                    video.srcObject = stream;
                    
                    video.onloadedmetadata = function() {
                        console.log('Camera đã sẵn sàng:', video.videoWidth + 'x' + video.videoHeight);
                        updateCameraStatus('Sẵn sàng');
                        cameraApproved = true;
                        updatePermissionStatus();
                    };

                    video.onerror = function() {
                        console.error('Lỗi video stream');
                        updateCameraStatus('Lỗi video', true);
                        cameraApproved = false;
                        updatePermissionStatus();
                    };

                } catch (err) {
                    console.error('Lỗi khi truy cập camera:', err);
                    updateCameraStatus('Cần cấp quyền', true);
                    cameraApproved = false;
                    updatePermissionStatus();
                }
            }

            // Lấy vị trí hiện tại khi chụp ảnh
            function getCurrentLocation() {
                return new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        reject(new Error('Trình duyệt không hỗ trợ GPS'));
                        return;
                    }

                    const options = {
                        enableHighAccuracy: true,
                        timeout: isMobileDevice() ? 30000 : 10000,
                        maximumAge: 60000
                    };

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;
                            
                            console.log('Vị trí đã lấy được:', { lat, lng, accuracy });
                            
                            resolve({
                                latitude: lat,
                                longitude: lng,
                                accuracy: accuracy
                            });
                        },
                        function(error) {
                            console.error('Lỗi khi lấy vị trí:', error);
                            let errorMessage = '';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage = 'Bị từ chối quyền truy cập vị trí';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage = 'Không thể xác định vị trí';
                                    break;
                                case error.TIMEOUT:
                                    errorMessage = 'Hết thời gian chờ GPS';
                                    break;
                                default:
                                    errorMessage = 'Lỗi không xác định';
                            }
                            reject(new Error(errorMessage));
                        },
                        options
                    );
                });
            }

            // Hiển thị thông tin vị trí trong preview
            function displayLocationInfo(locationData) {
                if (locationText) {
                    locationText.textContent = '✅ Đã xác định';
                    locationText.className = 'text-green-400 font-medium';
                }
                
                if (locationDetails) {
                    locationDetails.classList.remove('hidden');
                    if (longitudeText) longitudeText.textContent = locationData.longitude.toFixed(6);
                    if (latitudeText) latitudeText.textContent = locationData.latitude.toFixed(6);
                }
            }

            // Hiển thị lỗi vị trí trong preview
            function displayLocationError(errorMessage) {
                if (locationText) {
                    locationText.textContent = '❌ ' + errorMessage;
                    locationText.className = 'text-red-400 font-medium';
                }
                
                if (locationDetails) {
                    locationDetails.classList.add('hidden');
                }
            }

            // Chụp ảnh và lấy vị trí
            if (captureBtn) {
                captureBtn.addEventListener('click', async function() {
                    try {
                        if (video.readyState < 2) {
                            alert('Camera chưa sẵn sàng. Vui lòng đợi một chút.');
                            return;
                        }

                        // Disable button và hiển thị loading
                        captureBtn.disabled = true;
                        captureBtn.innerHTML = `
                            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Đang chụp ảnh và xác định vị trí...
                        `;

                        // Chụp ảnh
                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                        capturedImage = canvas.toDataURL('image/jpeg', 0.8);
                    image1Input.value = capturedImage;
                    
                    // Hiển thị preview
                    previewImage.src = capturedImage;
                    preview.classList.remove('hidden');
                    video.parentElement.classList.add('hidden');
                        
                        // Lấy vị trí
                        try {
                            const locationData = await getCurrentLocation();
                            
                            // Lưu vị trí vào form
                            latitudeInput.value = Number(locationData.latitude).toFixed(6);
                            longitudeInput.value = Number(locationData.longitude).toFixed(6);
                            if (longitudeText) longitudeText.textContent = locationData.longitude.toFixed(6);
                            if (latitudeText) latitudeText.textContent = locationData.latitude.toFixed(6);
                            locationDetails.classList.remove('hidden');
                            
                            // Hiển thị thông tin vị trí
                            displayLocationInfo(locationData);
                            
                        } catch (locationError) {
                            console.error('Lỗi vị trí:', locationError);
                        displayLocationError(locationError.message);
                            
                            // Vẫn cho phép submit nhưng cảnh báo
                            alert('Không thể xác định vị trí: ' + locationError.message + '\nBạn vẫn có thể chấm công nhưng vị trí sẽ không được ghi nhận.');
                        }
                    
                    // Ẩn nút chụp, hiện form submit
                        captureBtn.parentElement.classList.add('hidden');
                    attendanceForm.classList.remove('hidden');
                        
                        console.log('Đã chụp ảnh thành công:', canvas.width + 'x' + canvas.height);
                        
                    } catch (error) {
                        console.error('Lỗi khi chụp ảnh:', error);
                        alert('Có lỗi khi chụp ảnh. Vui lòng thử lại.');
                        
                        // Reset button
                        captureBtn.disabled = false;
                        captureBtn.innerHTML = `
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-semibold">📸 Chụp ảnh & Chấm công</span>
                        `;
                    }
                });
            }

            // Submit form
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    if (!capturedImage) {
                        alert('Vui lòng chụp ảnh trước khi chấm công');
                        e.preventDefault();
                        return;
                    }
                    // Không cần gọi API so sánh khuôn mặt, chỉ submit form truyền thống
                });
            }

            // Khởi tạo
            initCamera();

            // Xử lý khi trang bị ẩn/hiện (cho mobile)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }
                } else {
                    if (!stream || stream.getTracks().every(track => track.readyState === 'ended')) {
                        initCamera();
                    }
                }
            });

            // Cleanup khi rời trang
            window.addEventListener('beforeunload', function() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            });

            // Thêm hàm cập nhật trạng thái camera
            function updateCameraStatus(message, isError = false) {
                if (cameraStatusText) {
                    cameraStatusText.textContent = message;
                    cameraStatusText.style.color = isError ? 'red' : '';
                }
            }
        });
    </script>
</x-app-layout>