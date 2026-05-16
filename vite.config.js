import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    build: {
        target: ['es2020'],
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        preserveSymlinks: true,
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            '@shared-saas': resolve(__dirname, 'vendor/sicaboy/shared-saas/resources/js'),
        },
    },
});
