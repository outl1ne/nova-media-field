let mix = require('laravel-mix');

mix
  .setPublicPath('dist')
  .js('resources/js/field.js', 'js')
  .js('resources/js/urlField.js', 'js')
  .js('resources/js/toolbar.js', 'js')
  .sass('resources/sass/field.scss', 'css');
