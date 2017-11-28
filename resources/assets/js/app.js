
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');

 window.Vue = require('vue');

 import VueRouter from 'vue-router';
 import VueSwal from 'vue-swal'
 import Spinner from 'vue-simple-spinner'
 import Datepicker from 'vuejs-datepicker';


 window.Vue.use(VueSwal)
 window.Vue.use(Spinner)

 Vue.component('pagination', require('laravel-vue-pagination'));
 Vue.component('vue-simple-spinner',require('vue-simple-spinner'))
 Vue.component('selectize-component', require('vue2-selectize'));
 Vue.component('datepicker', require('vuejs-datepicker'));

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
 
 /**User*/
 import UserIndex from './components/user/UserIndex.vue';
 import UserCreate from './components/user/UserCreate.vue';
 import UserEdit from './components/user/UserEdit.vue';
 
 /**Customer*/
 import CustomerIndex from './components/customer/CustomerIndex.vue';
 import CustomerCreate from './components/customer/CustomerCreate.vue';
 import CustomerEdit from './components/customer/CustomerEdit.vue';
 import CustomerDetail from './components/customer/CustomerDetail.vue';
 
 /**Error Log*/
 import ErrorIndex from './components/error/ErrorIndex.vue';

 /**Warung*/
 import WarungIndex from './components/warung/WarungIndex.vue';
 import WarungCreate from './components/warung/WarungCreate.vue';
 import WarungEdit from './components/warung/WarungEdit.vue';

 /**KOMUNITAS*/
 import KomunitasIndex from './components/komunitas/KomunitasIndex.vue';
 import KomunitasCreate from './components/komunitas/KomunitasCreate.vue';
 import KomunitasEdit from './components/komunitas/KomunitasEdit.vue';
 import KomunitasDetail from './components/komunitas/KomunitasDetail.vue';

 /**USER WARUNG*/
 import UserWarungIndex from './components/user_warung/UserWarungIndex.vue';
 import UserWarungEdit from './components/user_warung/UserWarungEdit.vue';

 /**kelompok produk*/
 import KelompokProdukIndex from './components/kelompok_produk/KelompokProdukIndex.vue';
 import KelompokProdukCreate from './components/kelompok_produk/KelompokProdukCreate.vue';
 import KelompokProdukEdit from './components/kelompok_produk/KelompokProdukEdit.vue';

 // UBAH PROFIL ADMIN
 import UbahProfilAdmin from './components/ubah_profil/UbahProfilAdmin.vue';

 // UBAH PASSSWORD
 import UbahPasswordAdmin from './components/ubah_password/UbahPasswordAdmin.vue';

 // UBAH DATA PROFIL WARUNG
 import ProfilWarungIndex from './components/warung_profil/ProfilWarungIndex.vue';
 import ProfilWarungEdit from './components/warung_profil/ProfilWarungEdit.vue';

 // KATEGORI TRANSAKSI
 import KategoriTransaksiIndex from './components/kategori_transaksi/KategoriTransaksiIndex.vue';
 import KategoriTransaksiCreate from './components/kategori_transaksi/KategoriTransaksiCreate.vue';
 import KategoriTransaksiEdit from './components/kategori_transaksi/KategoriTransaksiEdit.vue';

 const routes = [ 
 {
 	path: '/',
 	components: {
 		dashboardIndex: DashboardAdminIndex
 	},
 	name : 'indexDashboard'
 },  
 {path: '/create-bank', component: BankCreate, name: 'createBank'},
 {path: '/satuan', component: SatuanIndex, name: 'indexSatuan'},
 {path: '/bank', component: BankIndex, name: 'indexBank'},
 {path: '/user', component: UserIndex, name: 'indexUser'},
 /*CUSTOMER*/
 {path: '/customer', component: CustomerIndex, name: 'indexCustomer'},
 /*ERROR LOG*/
 {path: '/error', component: ErrorIndex, name: 'indexError'},



 {path: '/edit-bank/:id', component: BankEdit, name: 'editBank'},
 {path: '/lazy_load', component: LazyIndex, name: 'indexLazy'},
 {path: '/create-satuan', component: SatuanCreate, name: 'createSatuan'},
 {path: '/edit-satuan/:id', component: SatuanEdit, name: 'editSatuan'},
 /*CUSTOMER*/
 {path: '/create-customer', component: CustomerCreate, name: 'createCustomer'},
 {path: '/edit-customer/:id', component: CustomerEdit, name: 'editCustomer'},
 {path: '/detail-customer/:id', component: CustomerDetail, name: 'detailCustomer'},


 {path: '/create-user', component: UserCreate, name: 'createUser'},
 {path: '/edit-user/:id', component: UserEdit, name: 'editUser'},

 /**Warung*/
 {path: '/warung', component: WarungIndex, name: 'indexWarung'},
 {path: '/create-warung', component: WarungCreate, name: 'createWarung'},
 {path: '/edit-warung/:id', component: WarungEdit, name: 'editWarung'},

 /**USER WARUNG*/
 {path: '/user-warung', component: UserWarungIndex, name: 'indexUserWarung'},
 {path: '/edit-user-warung/:id', component: UserWarungEdit, name: 'editUserWarung'},

// Komunitas
{path: '/komunitas', component: KomunitasIndex, name: 'indexKomunitas'},
{path: '/create-komunitas', component: KomunitasCreate, name: 'createKomunitas'},
{path: '/edit-komunitas/:id', component: KomunitasEdit, name: 'editKomunitas'},
{path: '/detail-komunitas/:id', component: KomunitasDetail, name: 'detailKomunitas'},

// kelompok produk

{path: '/kelompok-produk', component: KelompokProdukIndex, name: 'indexKelompokProduk'},
{path: '/create-kelompok-produk', component: KelompokProdukCreate, name: 'createKelompokProduk'},
{path: '/edit-kelompok-produk/:id', component: KelompokProdukEdit, name: 'editKelompokProduk'},

// ubah profil admin
{path: '/ubah-profil-admin', component: UbahProfilAdmin, name: 'ubahProfilAdmin'},

// ubah password admin
{path: '/ubah-password-admin', component: UbahPasswordAdmin, name: 'ubahPasswordAdmin'},

// Ubah Data Profil Warung
{path: '/profil-warung', component: ProfilWarungIndex, name: 'indexProfilWarung'},
{path: '/edit-profil-warung/:id', component: ProfilWarungEdit, name: 'editProfilWarung'},

// Kategori Transaksi
{path: '/kategori-transaksi', component: KategoriTransaksiIndex, name: 'indexKategoriTransaksi'},
{path: '/create-kategori-transaksi', component: KategoriTransaksiCreate, name: 'createKategoriTransaksi'},
{path: '/edit-kategori-transaksi/:id', component: KategoriTransaksiEdit, name: 'editKategoriTransaksi'},

]


const router = new VueRouter({ routes })

const app = new Vue({ router }).$mount('#vue-app')
