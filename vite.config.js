import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: '0.0.0.0',  // Esta linha é crucial para Docker!
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },
});
