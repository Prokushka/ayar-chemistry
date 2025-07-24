import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'node:path';

export default defineConfig(({ ssrBuild }) => {
  return {
    plugins: [
      laravel({
        input: 'resources/js/app.js',
        ssr: 'resources/js/ssr.js',
        refresh: true,
      }),
      vue({
        template: {
          transformAssetUrls: {
            base: null,
            includeAbsolute: false,
          },
        },
      })
    ],
    resolve: {
      alias: {
        ziggy: resolve('vendor/tightenco/ziggy/dist/vue.es.js'),
	    '@components': resolve(__dirname, 'resources/js/components'),
        '@css': resolve(__dirname, 'resources/css'),
        '@assets': resolve(__dirname, 'resources/assets'),
	    '@': resolve(__dirname, 'resources/js'),
      },
    }
  };
});
