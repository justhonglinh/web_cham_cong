<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo - Chấm công với Face Recognition & Location Validation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">🚀 Demo: Chấm công thông minh</h1>
                <p class="text-gray-600 text-lg">Tích hợp Face Recognition + Location Validation</p>
            </div>

            <!-- Demo Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Face Recognition Card -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-blue-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Face Recognition</h2>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>So sánh với ảnh đại diện</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>Sử dụng Face++ API</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>Ngưỡng tin cậy: 70%</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>Lưu ảnh chấm công</span>
                        </div>
                    </div>
                </div>

                <!-- Location Validation Card -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-green-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Location Validation</h2>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>Kiểm tra với quản lý</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>Phạm vi làm việc</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>GPS tracking</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>Haversine distance</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Flow -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-purple-200 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Quy trình chấm công
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 font-bold">1</div>
                        <p class="text-sm text-gray-600">Chụp ảnh</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 font-bold">2</div>
                        <p class="text-sm text-gray-600">So sánh khuôn mặt</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 font-bold">3</div>
                        <p class="text-sm text-gray-600">Kiểm tra vị trí</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-yellow-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 font-bold">4</div>
                        <p class="text-sm text-gray-600">Xác định trạng thái</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-red-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 font-bold">5</div>
                        <p class="text-sm text-gray-600">Lưu chấm công</p>
                    </div>
                </div>
            </div>

            <!-- Demo Accounts -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-orange-200 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Tài khoản Demo
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <h3 class="font-semibold text-blue-800 mb-2">👨‍💼 Quản lý</h3>
                        <p class="text-sm text-gray-600 mb-1"><strong>Email:</strong> manager@example.com</p>
                        <p class="text-sm text-gray-600 mb-1"><strong>Password:</strong> password</p>
                        <p class="text-sm text-gray-600"><strong>Chức năng:</strong> Thiết lập vị trí làm việc</p>
                    </div>
                    
                    <div class="bg-green-50 rounded-xl p-4">
                        <h3 class="font-semibold text-green-800 mb-2">👩‍💻 Nhân viên</h3>
                        <p class="text-sm text-gray-600 mb-1"><strong>Email:</strong> employee@example.com</p>
                        <p class="text-sm text-gray-600 mb-1"><strong>Password:</strong> password</p>
                        <p class="text-sm text-gray-600"><strong>Chức năng:</strong> Chấm công với camera</p>
                    </div>
                </div>
            </div>

            <!-- Test Buttons -->
            <div class="text-center space-y-4">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('employees.attendance') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        🚀 Test Chấm công
                    </a>
                    
                    <a href="{{ route('locations.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        📍 Quản lý vị trí
                    </a>
                </div>
                
                <p class="text-sm text-gray-500">
                    💡 <strong>Lưu ý:</strong> Để test đầy đủ, bạn cần đăng nhập với tài khoản nhân viên và đảm bảo có ảnh đại diện
                </p>
            </div>
        </div>
    </div>
</body>
</html> 