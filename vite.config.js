import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    rollupOptions: {
      input: 'resources/js/main.js',
      output: {
        assetFileNames: 'async-select.css',
      }
    },
    minify: true
  },
  css: {
    postcss: './postcss.config.js'
  }
});
