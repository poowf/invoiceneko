const mix = require('laravel-mix');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const imageminMozjpeg = require('imagemin-mozjpeg');

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
    .setPublicPath('public')
    .options({
        fileLoaderDirs: {
            fonts: 'assets/fonts'
        }
    })
    .webpackConfig({
        plugins: [
            new CopyWebpackPlugin([{
                from: 'resources/assets/images',
                to: 'assets/img', // Laravel mix will place this in 'public/img'
            }]),
            new ImageminPlugin({
                test: /\.(jpe?g|png|gif|svg)$/i,
                plugins: [
                    imageminMozjpeg({
                        quality: 100,
                    })
                ]
            })
        ]
    })

    .js('resources/assets/js/app.js', 'assets/js')
    .js('node_modules/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js', 'assets/js')
    .js('node_modules/trumbowyg/dist/plugins/cleanpaste/trumbowyg.cleanpaste.min.js', 'assets/js')
    .js('node_modules/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js', 'assets/js')
    .js('node_modules/trumbowyg/dist/plugins/history/trumbowyg.history.min.js', 'assets/js')
    .js('node_modules/intl-tel-input/build/js/intlTelInput.js', 'assets/js')
    .js('node_modules/intl-tel-input/build/js/utils.js', 'assets/js')

    .sass('resources/assets/sass/core.scss', 'public/assets/css')
    .sass('resources/assets/sass/style.scss', 'public/assets/css')
    .sass('node_modules/materialize-css/sass/materialize.scss', 'assets/css')
    .sass('node_modules/@mdi/font/scss/materialdesignicons.scss', 'assets/css')

    .sass('node_modules/trumbowyg/dist/ui/sass/trumbowyg.scss', 'assets/css')
    .sass('node_modules/trumbowyg/dist/plugins/colors/ui/sass/trumbowyg.colors.scss', 'assets/css')

    .sass('node_modules/slick-carousel/slick/slick.scss', 'assets/css')
    .sass('node_modules/slick-carousel/slick/slick-theme.scss', 'assets/css')
    .less('node_modules/selectize/dist/less/selectize.less', 'public/assets/css')
    .copy('node_modules/intl-tel-input/build/css/intlTelInput.css', 'public/assets/css/intlTelInput.css')
    .copy('node_modules/intl-tel-input/build/img/flags.png', 'public/assets/img/flags.png')
    .copy('node_modules/intl-tel-input/build/img/flags@2x.png', 'public/assets/img/flags@2x.png')
    .copy('node_modules/trumbowyg/dist/ui/icons.svg', 'public/assets/fonts/trumbowygicons.svg')
    .extract(['jquery'])
    .browserSync({
        //Browser Sync does not do any host or php processing, it just proxies the connection to the backend web server.
        //Port defines what port that browsersync will run on and it's basically a wrapper so that browsersync can be ran
        port: 8080,
        ui: {
            port: 8081
        },
        //When configuring proxy, remember that this must be added onto the hosts file on the VM also, otherwise browsersync will not be able to find a dns entry for it
        proxy: 'invoiceneko.test:80',
        open: false
    })
    .version();