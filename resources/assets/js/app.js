
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

/*require('./bootstrap');

window.Vue = require('vue');*/

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});
*/

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
window.Unicorn = require('./unicorn.js');
window.LogRocket = require('logrocket');
window.Clipboard = require('clipboard');
window.Chartist = require('chartist');
// window.Mark = require('mark.js');
// window.Sentry = require('sentry/node');
// window.Rollbar = require('rollbar');
//window.Laravel = { csrfToken: $('meta[name=csrf-token]').attr("content") };

require('../../../node_modules/mark.js/dist/jquery.mark.js');
require('materialize-css');
require('selectize');
require('parsleyjs');
require('slick-carousel');
require('trumbowyg');
require('prismjs');
require('chartist-plugin-legend');