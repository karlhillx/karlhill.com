import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import postcss from './postcss.config.mjs';

export default defineConfig({
    css: {
        postcss,
    },
  plugins: [
      laravel({
          input: [
              'resources/css/app.css',
              'resources/js/app.js',
          ],
          refresh: true,
      }),
  ]
});
