@if(session('success'))
    <div id="success-alert"
         class="fixed top-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md"
         role="alert"
         style="min-width: 220px; z-index: 1050; box-shadow: 0 4px 6px rgba(72, 187, 120, 0.5); transition: opacity 0.5s ease;">
        <strong class="font-bold">Success! </strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>

    <script>
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.pointerEvents = 'none';
                setTimeout(() => alert.remove(), 600);
            }
        }, 3000);
    </script>
@endif
