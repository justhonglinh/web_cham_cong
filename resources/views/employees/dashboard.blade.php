<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-500 to-pink-400 leading-tight drop-shadow-lg">
            {{ __('🌟 Dashboard nhân viên 🌟') }}
        </h2>
    </x-slot>

    <div class="py-10" style="background: linear-gradient(135deg, #e0e7ff 0%, #fdf2f8 100%); min-height: 100vh;">
        <div class="max-w-4xl mx-auto bg-white/90 rounded-2xl shadow-2xl p-8 border border-blue-100 backdrop-blur-md relative">
            <!-- Thời gian hiện tại góc phải -->
            <div id="current-time" class="absolute top-6 right-8 bg-white/70 px-4 py-2 rounded-lg shadow text-right text-gray-700 font-semibold text-base z-10" style="min-width: 180px;">
                <span id="date"></span><br>
                <span id="clock"></span>
            </div>
            <div class="flex items-center gap-8 mb-10">
                <div class="relative">
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-28 h-28 rounded-full border-4 border-blue-400 shadow-lg object-cover">
                    <span class="absolute bottom-2 right-2 w-5 h-5 bg-green-400 border-2 border-white rounded-full animate-pulse"></span>
                </div>
                <div>
                    <div class="font-extrabold text-2xl text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="flex gap-4 mt-2">
                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium shadow">Mã NV: {{ Auth::user()->employee_code ?? '' }}</span>
                        <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium shadow">Phòng: {{ Auth::user()->department ?? '' }}</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <a href="{{ route('employees.attendance') }}" class="flex flex-col items-center justify-center bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl p-6 font-bold text-lg shadow-lg hover:scale-105 hover:from-blue-600 hover:to-blue-800 transition-all duration-200">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/></svg>
                    Chấm công
                </a>
                <a href="{{ route('employees.attendance.history') }}" class="flex flex-col items-center justify-center bg-gradient-to-br from-green-400 to-green-600 text-white rounded-xl p-6 font-bold text-lg shadow-lg hover:scale-105 hover:from-green-500 hover:to-green-700 transition-all duration-200">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                    Lịch sử chấm công
                </a>
                <a href="{{ route('employees.overtime.index') }}" class="flex flex-col items-center justify-center bg-gradient-to-br from-yellow-400 to-yellow-600 text-white rounded-xl p-6 font-bold text-lg shadow-lg hover:scale-105 hover:from-yellow-500 hover:to-yellow-700 transition-all duration-200">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                    Ca làm thêm
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-gradient-to-br from-indigo-100 to-purple-50 rounded-xl p-6 shadow">
                    <h3 class="font-semibold text-lg text-indigo-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                        Thông báo mới
                    </h3>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        <li>Chào mừng bạn đến với hệ thống chấm công!</li>
                        <li>Nhớ chấm công đúng giờ mỗi ngày.</li>
                        <li>Chức năng nhận diện khuôn mặt đã được nâng cấp.</li>
                    </ul>
                </div>
                <div class="bg-gradient-to-br from-pink-100 to-yellow-50 rounded-xl p-6 shadow flex flex-col justify-center items-center">
                    <div class="text-4xl font-extrabold text-pink-500 mb-2">🎉</div>
                    <div class="text-lg font-semibold text-pink-700 mb-1">Chúc bạn một ngày làm việc hiệu quả!</div>
                    <div class="text-sm text-gray-500">Hãy luôn đúng giờ và giữ tinh thần tích cực nhé!</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateTime() {
            const now = new Date();
            const dateString = now.toLocaleDateString('vi-VN', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            const timeString = now.toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('date').textContent = dateString;
            document.getElementById('clock').textContent = timeString;
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</x-app-layout>