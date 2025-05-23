{{-- Hiển thị thông báo thành công --}}
@if(session('success'))
    <div id="success-alert"
         class="fixed top-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md"
         role="alert"
         style="min-width: 200px;">
        <strong class="font-bold">Success! </strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>

    <script>
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
@endif
