/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
//
// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });

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