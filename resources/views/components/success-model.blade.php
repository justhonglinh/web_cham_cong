@if(session('success'))
    <div id="success-alert"
         class="fixed top-5 right-5 bg-white dark:bg-gray-800 border-l-4 border-green-500 text-gray-700 dark:text-gray-200 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3"
         role="alert"
         style="min-width: 300px; z-index: 1050; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: all 0.5s ease;">
        <!-- Success Icon -->
        <div class="flex-shrink-0">
            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-500 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
        
        <!-- Message Content -->
        <div class="flex-1">
            <p class="font-semibold text-green-800 dark:text-green-300">Thành công!</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ session('success') }}</p>
        </div>

        <!-- Close Button -->
        <button onclick="closeAlert()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <script>
        function closeAlert() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                alert.style.pointerEvents = 'none';
                setTimeout(() => alert.remove(), 500);
            }
        }

        // Auto close after 5 seconds
        setTimeout(() => {
            closeAlert();
        }, 3000);
    </script>
@endif
