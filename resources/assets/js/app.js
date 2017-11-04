
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);
import VueRouter from 'vue-router';
import VueSwal from 'vue-swal'
import Spinner from 'vue-simple-spinner'
 

window.Vue.use(VueSwal)
window.Vue.use(Spinner)

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('vue-simple-spinner',require('vue-simple-spinner'))

window.Vue.use(VueRouter);

import BankCreate from './components/bank/BankCreate.vue';
import BankIndex from './components/bank/BankIndex.vue';
import BankEdit from './components/bank/BankEdit.vue';


/**Satuan*/
import SatuanIndex from './components/satuan/SatuanIndex.vue';
import SatuanCreate from './components/satuan/SatuanCreate.vue';
import SatuanEdit from './components/satuan/SatuanEdit.vue';
 

const routes = [ 
    {
        path: '/',
        components: {
            bankIndex: BankIndex,
            satuanIndex: SatuanIndex
        },
         name : 'indexBank',
         name : 'indexSatuan'
    },  
    {path: '/create', component: BankCreate, name: 'createBank'},
    {path: '/edit/:id', component: BankEdit, name: 'editBank'},
    {path: '/create_satuan', component: SatuanCreate, name: 'createSatuan'},
    {path: '/edit_satuan/:id', component: SatuanEdit, name: 'editSatuan'},
]


	const router = new VueRouter({ routes })

	const app = new Vue({ router }).$mount('#vue-app')
