import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: 'ict',
        port: 5173,
        cors: true,
        hmr: {
            host: 'ict',
            protocol: 'http',
            port: 5173,
        },
    },

    plugins: [
        laravel({
            input: [
                // Frontend pages
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
});