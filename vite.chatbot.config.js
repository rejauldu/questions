import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: 'ict',
        port: 5174,
        cors: true,
        hmr: {
            host: 'ict',
            protocol: 'http',
            port: 5174,
        },
    },

    plugins: [
        laravel({
            input: [
                // Chatbot + Auth pages
                'resources/css/app.css',
                'resources/js/chatbot.js',
            ],
            buildDirectory: 'build/chatbot',
            refresh: true,
        }),
        vue(),
        
    ],
});