
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
 import LazyIndex from './components/lazy_load/LazyIndex.vue';
 import DashboardAdminIndex from './components/dashboard/DashboardAdminIndex.vue';


 /**Satuan*/
 import SatuanIndex from './components/satuan/SatuanIndex.vue';
 import SatuanCreate from './components/satuan/SatuanCreate.vue';
 import SatuanEdit from './components/satuan/SatuanEdit.vue';
 

 const routes = [ 
 {
    path: '/',
    components: {
        dashboardIndex: DashboardAdminIndex
    },
    name : 'indexDashboard'
},  
{path: '/create', component: BankCreate, name: 'createBank'},
{path: '/satuan', component: SatuanIndex, name: 'indexSatuan'},
{path: '/bank', component: BankIndex, name: 'indexBank'},
{path: '/edit/:id', component: BankEdit, name: 'editBank'},
{path: '/lazy_load', component: LazyIndex, name: 'indexLazy'},
{path: '/create-satuan', component: SatuanCreate, name: 'createSatuan'},
{path: '/edit-satuan/:id', component: SatuanEdit, name: 'editSatuan'},

]


const router = new VueRouter({ routes })

const app = new Vue({ router }).$mount('#vue-app')
