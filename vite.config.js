import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/css/pagination-responsive.css'
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
            protocol: 'ws',
            port: 3000
        }
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();
                    }
                }
            }
        },
        commonjsOptions: {
            transformMixedEsModules: true
        },
        terserOptions: {
            compress: {
                drop_console: true,
            }
        }
    }
});
