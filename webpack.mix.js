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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    //.postCss('resources/css/app.css', 'public/css') 
    .sourceMaps();

mix.copy('node_modules/feather-icons/dist/icons', 'public/assets/svg/feather-icons');

mix.js('resources/js/modules/notyf.js', 'public/js/notyf.js');

mix.js('resources/js/modules/jquery.js', 'public/js/jquery.js');

mix.js('resources/js/modules/leaflet.js', 'public/js/leaflet.js');

mix.js('resources/js/modules/amcharts.js', 'public/js/amcharts.js');