const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
 const targetSVG = /(\.(png|jpe?g|gif|webp|avif)$|^((?!font).)*\.svg$)/;

 mix .js('resources/js/app.js', 'public/assets/js')
 .sass('resources/scss/notifications.scss', 'public/assets/css')
 .sass('resources/scss/app.scss', 'public/assets/css')

 mix.disableNotifications();