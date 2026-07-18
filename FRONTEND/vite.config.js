import { defineConfig } from 'vite';
import nunjucks from 'vite-plugin-nunjucks';

export default defineConfig({
  plugins: [
    nunjucks()
  ],
  build: {
    outDir: 'dist',
    rollupOptions: {
      input: {
        main: './index.html',
        toko: './toko.html',
        katalog: './katalog.html',
        detailCyprotil: './detail-cyprotil.html',
        produk: './produk.html',
        artikel: './artikel.html',
        chat: './chat.html',
        tentangkami: './tentangkami.html'
      }
    }
  }
});
