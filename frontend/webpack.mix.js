const mix = require('laravel-mix');
const path = require('path');

mix.js('assets/js/settings.jsx', 'dist/js')
.react()
.postCss('assets/css/app.css', 'dist/css', [
  require('tailwindcss'),
])
.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets'),
      '@/lib': path.resolve(__dirname, 'assets/lib'),
      '@/components': path.resolve(__dirname, 'assets/components'),
    },
  },
  externals: {
    'react': 'React',
    'react-dom': 'ReactDOM',
  },
})
.options({
  processCssUrls: false,
})
.sourceMaps(false, 'source-map');