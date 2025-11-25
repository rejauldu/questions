import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: 'ict',
        port: 5175,
        cors: true,
        hmr: {
            host: 'ict',
            protocol: 'http',
            port: 5175,
        },
    },

    plugins: [
        laravel({
            input: [
                // Dashboard pages
                'resources/css/dashboard.css',
                'resources/js/dashboard.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
});