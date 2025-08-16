import { fileURLToPath, URL } from 'node:url'
/// <reference types="vitest" />
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import svgLoader from 'vite-svg-loader'

export default defineConfig({
    server: {
        host: 'localhost',
    },
    plugins: [
        laravel({
            input: [
                'resources/scss/pdf.scss',
                'resources/scss/app.scss',
                'resources/app/app.ts',
            ],
            refresh: true,
        }),
        vue(),
        svgLoader({
            defaultImport: 'url' // or 'raw'
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources', import.meta.url)),
            '@app': fileURLToPath(new URL('./resources/app', import.meta.url)),
            '@components': fileURLToPath(new URL('./resources/app/components', import.meta.url)),
            '@icons': fileURLToPath(new URL('./public/icons', import.meta.url)),
            '@js': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '@lib': fileURLToPath(new URL('./resources/app/lib', import.meta.url)),
            '@root': fileURLToPath(new URL('./', import.meta.url)),
            '@store': fileURLToPath(new URL('./resources/app/store', import.meta.url)),
            '@types': fileURLToPath(new URL('./resources/types', import.meta.url)),
            '~bootstrap': fileURLToPath(new URL('node_modules/bootstrap', import.meta.url)),
        },
    },
    // @ts-ignore
    test: {
        setupFiles: './tests/test-setup.ts',
        environment: 'jsdom',
        globals: true
    }
})
