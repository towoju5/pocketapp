import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/trading-shell.js', 'resources/js/trading-dashboard.js'],
            refresh: true,
        }),
    ],
});
