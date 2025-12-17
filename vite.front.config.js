import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: 'examdao.com',
        port: 5173,
        cors: true,
        hmr: {
            host: 'examdao.com',
            protocol: 'https',
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
            buildDirectory: 'build/front',
            refresh: true,
        }),
        vue(),
    ],
});