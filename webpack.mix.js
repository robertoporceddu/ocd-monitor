let mix = require('laravel-mix');

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

mix.js('resources/assets/js/dial.js', 'public/js')
    .js('resources/assets/js/cpu.js', 'public/js')
    .js('resources/assets/js/available.js', 'public/js')
    .js('resources/assets/js/dial_status_log_5.js', 'public/js')
    .js('resources/assets/js/log.js', 'public/js')
    .js('resources/assets/js/mincpu.js', 'public/js')
    .js('resources/assets/js/main.js', 'public/js');
