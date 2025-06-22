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
        <button onclick="closeAlert('success-alert')" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

@if(session('warning'))
    <div id="warning-alert"
         class="fixed top-5 right-5 bg-white dark:bg-gray-800 border-l-4 border-yellow-500 text-gray-700 dark:text-gray-200 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3"
         role="alert"
         style="min-width: 300px; z-index: 1050; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: all 0.5s ease;">
        <!-- Warning Icon -->
        <div class="flex-shrink-0">
            <div class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
        </div>
        
        <!-- Message Content -->
        <div class="flex-1">
            <p class="font-semibold text-yellow-800 dark:text-yellow-300">Cảnh báo!</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ session('warning') }}</p>
        </div>

        <!-- Close Button -->
        <button onclick="closeAlert('warning-alert')" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

<script>
    function closeAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';
            alert.style.pointerEvents = 'none';
            setTimeout(() => alert.remove(), 500);
        }
    }

    // Auto close after 5 seconds
    setTimeout(() => {
        const successAlert = document.getElementById('success-alert');
        const warningAlert = document.getElementById('warning-alert');
        
        if (successAlert) {
            closeAlert('success-alert');
        }
        if (warningAlert) {
            closeAlert('warning-alert');
        }
    }, 5000);
</script>
