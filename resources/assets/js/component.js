window.Vue = require('vue');
import Vue from 'vue'

Vue.component('antrian', {
	props: ['list'],
	template: '\
	<tr>\
	<td>{{ list.no_antrian }}</td>\
	<td align="center">{{ list.pelanggan }}</td>\
	<td align="right">{{ list.total_belanja }}</td>\
	</tr>'
})

export default Vue