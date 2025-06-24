<!-- Location Modal -->
<div id="locationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-xl rounded-xl bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span id="locationModalTitle">Thêm vị trí chấm công</span>
                </h3>
                <button id="closeLocationModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="locationForm" class="space-y-5">
                @csrf
                
                <!-- Location Name -->
                <div>
                    <label for="location_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tên địa điểm <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="location_name" name="name" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="VD: Văn phòng chính, Chi nhánh 1">
                </div>

                <!-- Address -->
                <div>
                    <label for="location_address" class="block text-sm font-semibold text-gray-700 mb-2">
                        Địa chỉ <span class="text-red-500">*</span>
                    </label>
                    <textarea id="location_address" name="address" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                        placeholder="Nhập địa chỉ chi tiết"></textarea>
                </div>

                <!-- Coordinates -->
                <div class="grid grid-cols-2 gap-4 hidden">
                    <div>
                        <label for="location_latitude" class="block text-sm font-semibold text-gray-700 mb-2">
                            Vĩ độ
                        </label>
                        <input type="number" id="location_latitude" name="latitude" step="any"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="10.762622">
                    </div>
                    <div>
                        <label for="location_longitude" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kinh độ
                        </label>
                        <input type="number" id="location_longitude" name="longitude" step="any"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="106.660172">
                    </div>
                </div>

                <!-- Radius -->
                <div>
                    <label for="location_radius" class="block text-sm font-semibold text-gray-700 mb-2">
                        Bán kính cho phép (mét)
                    </label>
                    <input type="number" id="location_radius" name="radius" value="100" min="10" max="1000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Description -->
                <div>
                    <label for="location_description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Mô tả thêm
                    </label>
                    <textarea id="location_description" name="description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                        placeholder="Mô tả thêm về địa điểm này"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <!-- Get Current Location Button -->
                        <button type="button" id="getCurrentLocation" 
                            class="flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-xs font-medium w-32 h-10">
                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-center leading-tight">Lấy vị trí hiện tại</span>
                        </button>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium w-20 h-10">
                            Lưu 
                        </button>
                        <button type="button" id="cancelLocation" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-medium w-20 h-10">
                            Hủy
                        </button>
                    </div>
                </div>
            </form>

            <!-- Loading Indicator -->
            <div id="locationLoading" class="hidden text-center py-8">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-blue-600 font-medium">Đang xử lý...</span>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <div id="locationMessage" class="hidden mt-6 p-4 rounded-lg font-medium"></div>
        </div>
    </div>
</div>

<!-- Existing Locations Modal -->
<div id="existingLocationsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-xl rounded-xl bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Vị trí có sẵn
                </h3>
                <button id="closeExistingLocationsModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Locations List -->
            <div id="existingLocationsList" class="space-y-3 max-h-80 overflow-y-auto">
                <!-- Locations will be loaded here -->
            </div>

            <!-- No Locations Message -->
            <div id="noLocationsMessage" class="hidden text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-lg font-medium">Chưa có vị trí nào được lưu</p>
                <p class="text-sm text-gray-400 mt-2">Hãy tạo vị trí đầu tiên để bắt đầu</p>
            </div>
        </div>
    </div>
</div> 