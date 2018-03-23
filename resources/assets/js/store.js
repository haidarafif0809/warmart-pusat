import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

//State adalah sebuah object dimana tempat semua data aplikasi berada, state ini sebuah json yang berisi data-data yang akan kita akses dari component yang ada
//  cara mengaksesnya state langsung di setiap component dengan cara this.$store.state.namaState =>contoh  this.$store.state.default_kas.
const state = {
	default_kas : '',
	produk : [],
	produk_laporan : [],
	kas : [],
	pelanggan : [],
	kasir:[],
	suplier : [],
	bank : []
}
//Getter berfungsi untuk mengakses state
// Dengan menggunakan Getter kita bisa mengolah terlebih dahulu state yang akan kita ambil seperti fungsi computed yang ada di VueJS. Jadi kita bisa memfilter data state sebelum di panggil. 
const getters = {
	// filter produk yg berkaitan dengan stok dan produk yg bisa dijual
	produkStok(state){
		return state.produk.filter(function(produk){
			return produk.hitung_stok == 1 && produk.status_aktif == 1
		})
	},
	produk_barang(state){
		return state.produk.filter(function(produk){
			return produk.hitung_stok == 1 && produk.status_aktif == 1
		})
	},
	produk_setting(state){
		return state.produk_laporan.filter(function(produk_laporan){
			return produk_laporan.id != ""
		})
	},
	suplier_pembelian(state){
		return state.suplier.filter(function(suplier){
			return suplier.id != "";
		})
	},
	pelangganTransaksi(state){
		return state.pelanggan.filter(function(pelanggan){
			return pelanggan.id != "";
		})
	}
}
// Mutation adalah satu-satunya cara untuk merubah state
const mutations = {
	// untuk memuat data produk
	SET_PRODUK_LIST : (state, { list }) => {
		state.produk = list
	},
	SET_PRODUK_LAPORAN_LIST : (state, { list }) => {
		state.produk_laporan = list
	},
	// untuk memuat data pelanggan
	SET_PELANGGAN_LIST : (state, { list }) => {
		state.pelanggan = list
	},
	// untuk memuat data kas
	SET_KAS_LIST : (state, { list }) => {
		state.kas = list
	},
	// untuk memuat data kasir
	SET_KASIR_LIST : (state, { list }) => {
		state.kasir = list
	},
	// untuk memuat data suplier
	SET_SUPLIER_LIST : (state, { list }) => {
		state.suplier = list
	},
	// untuk mengetahui default kas
	SET_DEFAULT_KAS (state,default_kas) {
		state.default_kas = default_kas
	},
	// untuk memuat daftar bank
	SET_BANK_LIST : (state, { list }) => {
		state.bank = list
	},

}
// Action mirip dengan mutation
// Nah action ini tidak merubah state, tapi action akan memanggil mutation dan mutation yang akan merubah state.
const actions = {

	LOAD_PRODUK_LIST : function({commit}){
		axios.get('produk/pilih-produk')
		.then((resp) => {
			commit('SET_PRODUK_LIST',
			{
				list:resp.data
			})
		},
		(err) => {
			console.log(err)
		})
	},
	LOAD_PRODUK_LAPORAN_LIST : function({commit}){
		axios.get('laporan-laba-kotor-produk/pilih-produk')
		.then((resp) => {
			commit('SET_PRODUK_LAPORAN_LIST',
			{
				list:resp.data
			})
		},
		(err) => {
			console.log(err)
		})
	},
	LOAD_PELANGGAN_LIST : function({commit}){
		axios.get('penjualan/pilih-pelanggan')
		.then((resp) => {
			commit('SET_PELANGGAN_LIST',
			{
				list:resp.data
			})
		},
		(err) => {
			console.log(err)
		})
	},
	LOAD_KASIR_LIST : function({commit}){
		axios.get('penjualan/pilih-kasir')
		.then((resp) => {
			commit('SET_KASIR_LIST',
			{
				list:resp.data
			})
		},
		(err) => {
			console.log(err)
		})
	},
	LOAD_KAS_LIST : function({commit}){
		axios.get('kas/daftar-kas')
		.then((resp) => {
			commit('SET_KAS_LIST',
			{
				list:resp.data.kas
			}),
			commit('SET_DEFAULT_KAS',	resp.data.default_kas)
		},
		(err) => {
			console.log(err)
		})
	},
	LOAD_SUPLIER_LIST : function({commit}){
		axios.get('suplier/pilih-suplier')
		.then((resp) => {
			commit('SET_SUPLIER_LIST',
			{
				list:resp.data
			})
		},
		(err) => {
			console.log(err)
		})
	},
	LOAD_BANK_LIST : function({commit}){
		axios.get('bank-warung/bank')
		.then((resp) => {
			commit('SET_BANK_LIST',
			{
				list:resp.data
			})
		},
		(err) => {
			console.log(err)
		})
	}
}

export default new Vuex.Store({
	state,
	getters,
	mutations,
	actions
})