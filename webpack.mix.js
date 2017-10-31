let mix = require('laravel-mix');
let path = require('path');

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
    .js('node_modules/mark.js/dist/jquery.mark.min.js', 'public/assets/js')
    .sass('resources/assets/sass/core.scss', 'public/assets/css')
    .sass('resources/assets/sass/style.scss', 'public/assets/css')
    .less('node_modules/selectize/dist/less/selectize.less', 'public/assets/css')
    .webpackConfig({
        resolve: {
            alias: {
                'jquery': path.join( __dirname, 'node_modules/jquery/dist/jquery' ),
            }
        }
    })
    .browserSync({
        //Browser Sync does not do any host or php processing, it just proxies the connection to the backend web server.
        //Port defines what port that browsersync will run on and it's basically a wrapper so that browsersync can be ran
        port: 8080,
        ui: {
            port: 8081
        },
        //When configuring proxy, remember that this must be added onto the hosts file on the VM also, otherwise browsersync will not be able to find a dns entry for it
        proxy: 'invoiceplz.site:80',
        open: false
    })
    .version();