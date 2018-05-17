window.Vue = require('vue');
import Vue from 'vue'

Vue.component('antrian', {
	props: ['list'],
	template: '\
	<tr>\
	<td>{{ list.no_antrian }}</td>\
	<td align="center">{{ list.pelanggan }}</td>\
	<td align="right">{{ list.total_belanja }}</td>\
	<td align="right">\
	<a href="#create-penjualan" class="btn btn-xs btn-danger" v-on:click="deleteAntrian">Delete</a>\
	</td>\
	</tr>',
	methods: {
		deleteAntrian(){
			this.$emit('deleteAntrian',this.list)
		}
	}
})

export default Vue