import Vue from 'vue'
import Vuex from 'vuex'

import axios from 'axios'

Vue.use(Vuex)

//State adalah sebuah object dimana tempat semua data aplikasi berada, state ini sebuah json yang berisi data-data yang akan kita akses dari component yang ada
//  cara mengaksesnya state langsung di setiap component dengan cara this.$store.state.namaState =>contoh  this.$store.state.default_kas.
const state = {
	default_kas : '',
	produk : [],
	kas : [],
	pelanggan : []
}
//Getter berfungsi untuk mengakses state
// Dengan menggunakan Getter kita bisa mengolah terlebih dahulu state yang akan kita ambil seperti fungsi computed yang ada di VueJS. Jadi kita bisa memfilter data state sebelum di panggil. 
const getters = {
	produk_barang(state){
		return state.produk.filter(function(produk){
			return produk.hitung_stok == 1
    	})
  	},
}
// Mutation adalah satu-satunya cara untuk merubah state
const mutations = {
	// untuk memuat data produk
	SET_PRODUK_LIST : (state, { list }) => {
		state.produk = list
	},
	// untuk memuat data pelanggan
	SET_PELANGGAN_LIST : (state, { list }) => {
		state.pelanggan = list
	},
	// untuk memuat data kas
	SET_KAS_LIST : (state, { list }) => {
		state.kas = list
	},
	// untuk mengetahui default kas
	SET_DEFAULT_KAS (state,default_kas) {
		state.default_kas = default_kas
	}

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
	}
}

export default new Vuex.Store({
	state,
	getters,
	mutations,
	actions
})