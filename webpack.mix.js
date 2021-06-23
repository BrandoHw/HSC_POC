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

mix.copy('node_modules/feather-icons/dist/icons', 'public/assets/svg/feather-icons');

/* Comment out because it is already included in the form of npm */
mix.js('resources/js/modules/moment.js', 'public/js/mix/moment.js');
mix.js('resources/js/modules/input-spinner.js', 'public/js/mix/input-spinner.js');
mix.js('resources/js/modules/daterangepicker.js', 'public/js/mix/daterangepicker.js');
mix.js('resources/js/modules/flatpickr.js', 'public/js/mix/flatpickr.js');
mix.js('resources/js/modules/stepper.js', 'public/js/mix/stepper.js');
mix.js('resources/js/modules/leaflet.js', 'public/js/mix/leaflet.js');
mix.js('resources/js/modules/amcharts.js', 'public/js/mix/amcharts.js');
mix.js('resources/js/modules/apexcharts.js', 'public/js/mix/apexcharts.js');
mix.js('resources/js/modules/pdfmake.js', 'public/js/mix/pdfmake.js');
mix.js('resources/js/modules/jszip.js', 'public/js/mix/jszip.js');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css/app.css')
    .sourceMaps();
// mix.js('resources/js/modules/jquery.js', 'public/js/jquery.js');

// mix.js('resources/js/modules/leaflet.js', 'public/js/leaflet.js');

// mix.js('resources/js/modules/amcharts.js', 'public/js/amcharts.js');