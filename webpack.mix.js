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

mix.setPublicPath('public');
mix.setResourceRoot('../');
/*mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');*/
mix.js('resources/js/app.js', 'public/js');


/** Admin START */
/*mix.styles([
        'public/backend/assets/plugins/jquery-ui/jquery-ui.min.css',
        'public/backend/assets/plugins/bootstrap/css/bootstrap.min.css',
        'public/backend/assets/plugins/font-awesome/css/all.min.css',
        'public/backend/assets/plugins/animate/animate.min.css',
        'public/backend/assets/css/default/style.min.css',
        'public/backend/assets/css/default/style-responsive.min.css',
        'public/backend/assets/css/default/theme/default.css',
    ], 'public/backend/css/all.css')
    .options({
          processCssUrls: true
       });*/
/** Admin END */

/*mix.styles([
        'public/frontend/css/normalize.css',
        'public/frontend/css/font-awesome.min.css',
        'public/frontend/css/owl.carousel.min.css',
        'public/frontend/css/bootstrap.min.css',
    ], 'public/frontend/css/all.css');

mix.scripts([
    'public/frontend/js/vendor/modernizr-3.7.1.min.js',
    'public/frontend/js/plugins.js',
    'public/frontend/js/bootstrap.min.js',
    'public/frontend/js/owl.carousel.min.js',
], 'public/frontend/js/all.js');*/
