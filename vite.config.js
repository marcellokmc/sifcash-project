import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig(({ command, mode }) => {
    const plugins = [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/components/admin.js',
                'resources/js/components/charts.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ];
    
    // Ajouter Bundle Analyzer en mode analyse
    if (process.env.ANALYZE === 'true' || mode === 'analyze') {
        plugins.push(
            visualizer({
                filename: 'dist/bundle-analysis.html',
                open: true,
                gzipSize: true,
                brotliSize: true,
                template: 'treemap', // 'treemap', 'sunburst', 'network'
                title: 'SIFCash-Burkina - Bundle Analysis'
            })
        );
    }
    
    return {
        plugins,
    
    build: {
        rollupOptions: {
            output: {
                // Nommage optimisé des chunks
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(ext)) {
                        return `img/[name]-[hash][extname]`;
                    }
                    if (/css/i.test(ext)) {
                        return `css/[name]-[hash][extname]`;
                    }
                    return `assets/[name]-[hash][extname]`;
                }
            }
        },
        // Optimisations
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        // Code splitting
        chunkSizeWarningLimit: 1000,
        sourcemap: false, // Désactiver en production
    },
    
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            '@css': resolve(__dirname, 'resources/css'),
            '@components': resolve(__dirname, 'resources/js/components'),
        }
    },
    
    // Optimisation du serveur de développement
    server: {
        hmr: {
            overlay: false
        },
        watch: {
            ignored: ['**/vendor/**', '**/node_modules/**']
        }
    },
    
    // Optimisations CSS
    css: {
        devSourcemap: true,
        preprocessorOptions: {
            scss: {
                additionalData: `@import "@css/variables.scss";`
            }
        }
    }
    };
});
