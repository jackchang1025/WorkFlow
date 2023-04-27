import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import legacy from 'vite-plugin-legacy';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
        legacy({
            targets: ['defaults'],
            additionalLegacyPolyfills: ['regenerator-runtime/runtime'],
        }),
    ],
    watch: {
        include: 'resources/**/*',
        exclude: ['vendor/**/*', 'node_modules/**/*'],
        usePolling: true, // 启用轮询
        interval: 500, // 轮询间隔，单位为毫秒，默认值是 100
    },
    server: {
        host: '192.168.10.10', // 允许外部访问
        port: 5173, // 你可以选择任何可用的端口
        open: false, // 可选，自动打开浏览器
        cors: true, // 允许跨域请求
        clearScreen: false // 禁止清除控制台屏幕
    },
});
