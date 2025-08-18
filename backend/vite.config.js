import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['axios']
                }
            }
        },
        // Use esbuild minifier and drop console/debugger in production
        minify: 'esbuild'
    },
    esbuild: {
        drop: ['console', 'debugger']
    }
});
