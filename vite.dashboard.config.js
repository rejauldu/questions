import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: 'examdao.com',
        port: 5175,
        cors: true,
        hmr: {
            host: 'examdao.com',
            protocol: 'https',
            port: 5175,
        },
    },

    plugins: [
        laravel({
            input: [
                // Dashboard pages
                'resources/css/app.css',
                'resources/js/dashboard.js',
            ],
            buildDirectory: 'build/dashboard',
            refresh: true,
        }),
        vue(),
    ],
});