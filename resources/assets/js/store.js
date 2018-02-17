import Vue from 'vue'
import Vuex from 'vuex'

import axios from 'axios'

Vue.use(Vuex)

const state = {
	produk : []
}
const getters = {

}
const mutations = {
	SET_PRODUK_LIST : (state, { list }) => {
		state.produk = list
	}
}
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
	}
}

export default new Vuex.Store({
	state,
	getters,
	mutations,
	actions
})