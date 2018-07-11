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
 import VueTour from 'vue-tour'
 import VueClipboard from 'vue-clipboard2'

// require styles
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import quillEditor from 'vue-quill-editor'
// chart.js
import VueChartJs from 'vue-chartjs'
// You need a specific loader for CSS files like https://github.com/webpack/css-loader
import routes from './router.js'
import store from './store'
import component from './component'
window.$ = window.jQuery = require('jquery');
// Require Froala Editor js file.
require('froala-editor/js/froala_editor.pkgd.min')
// Require Froala Editor css files.
require('froala-editor/css/froala_editor.pkgd.min.css')
require('font-awesome/css/font-awesome.css')
require('froala-editor/css/froala_style.min.css')
require('vue-tour/dist/vue-tour.css')
Vue.use(require('vue-chartist'))
Vue.use(ToggleButton)
Vue.use(VueFroala)
Vue.use(quillEditor)
Vue.use(VueTour)
Vue.use(VueClipboard);

// register directive v-money and component <money>
Vue.use(money, {
	precision: 4
})
var numeral = require("numeral");

Vue.filter("formatNumber", function (value) {
    return numeral(value).format("0,00.00"); // displaying other groupings/separators is possible, look at the docs
});
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('vue-simple-spinner', require('vue-simple-spinner'))
Vue.component('selectize-component', require('vue2-selectize'));
Vue.component('datepicker', require('vuejs-datepicker'));
Vue.component('vue-chartjs', require('vue-chartjs'));
Vue.use(require('vue-shortkey'))
window.Vue.use(VueSwal)
window.Vue.use(Spinner)
window.Vue.use(VueChartJs)
window.Vue.use(VueRouter);

const router = new VueRouter({
	routes
})
const app = new Vue({
	router,store, component
}).$mount('#vue-app')