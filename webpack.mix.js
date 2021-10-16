const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/js/app.js', 'public/js').version()
    .js('resources/js/panel.js', 'public/js').version()
    .sass('resources/sass/app.scss', 'public/css').version()
    .sass('resources/sass/fonts.scss', 'public/css').version()
    .sass('resources/sass/panel.scss', 'public/css').version()
    .sass('resources/sass/side-panel.scss', 'public/css').version();
