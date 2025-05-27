import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',   // Cho phép truy cập từ bên ngoài localhost
        port: 5173,        // Port mặc định Vite, bạn có thể đổi nếu muốn
        strictPort: true,  // Nếu port đang dùng thì lỗi, không tự động đổi port
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
