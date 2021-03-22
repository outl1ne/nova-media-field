let mix = require('laravel-mix');
let path = require('path');

mix
  .setPublicPath('dist')
  .webpackConfig({
    module: {
      rules: [
        {
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          include: path.resolve(__dirname, '/resources/js'),
        },
      ],
    },
  })
  .js('resources/js/field.js', 'js')
  .js('resources/js/url-field.js', 'js')
  .js('resources/js/toolbar.js', 'js')
  .sass('resources/sass/field.scss', 'css')
  .vue()
