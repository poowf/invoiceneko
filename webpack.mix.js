const { mix } = require('laravel-mix');
var path = require('path');

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
    .js('resources/assets/js/app.js', 'public/assets/js')
    .sass('resources/assets/sass/core.scss', 'public/assets/css')
    .sass('resources/assets/sass/style.scss', 'public/assets/css')
    .webpackConfig({
        resolve: {
            alias: {
                'jquery': path.join( __dirname, 'node_modules/jquery/dist/jquery' ),
            }
        }
    })
    .version();