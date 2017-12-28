/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router'
import VueSwal from 'vue-swal'
import Spinner from 'vue-simple-spinner'
import Datepicker from 'vuejs-datepicker'
import ToggleButton from 'vue-js-toggle-button'
import VueFroala from 'vue-froala-wysiwyg'
import Vue from 'vue'
import money from 'v-money'
// require styles
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import quillEditor from 'vue-quill-editor'
// You need a specific loader for CSS files like https://github.com/webpack/css-loader
import routes from './router.js'
window.$ = window.jQuery = require('jquery');
// Require Froala Editor js file.
require('froala-editor/js/froala_editor.pkgd.min')
// Require Froala Editor css files.
require('froala-editor/css/froala_editor.pkgd.min.css')
require('font-awesome/css/font-awesome.css')
require('froala-editor/css/froala_style.min.css')
Vue.use(ToggleButton)
Vue.use(VueFroala)
Vue.use(quillEditor)
// register directive v-money and component <money>
Vue.use(money, {
    precision: 4
})
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('vue-simple-spinner', require('vue-simple-spinner'))
Vue.component('selectize-component', require('vue2-selectize'));
Vue.component('datepicker', require('vuejs-datepicker'));
Vue.use(require('vue-shortkey'))
window.Vue.use(VueSwal)
window.Vue.use(Spinner)
window.Vue.use(VueRouter);
const router = new VueRouter({
    routes
})
const app = new Vue({
    router
}).$mount('#vue-app')