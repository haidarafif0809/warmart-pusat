window.Vue = require('vue');
import Vue from 'vue'

Vue.component('antrian', {
	props: ['todo'],
	template: '\
	<div class="table-responsive">\
	<div style="color: red; float: right;">\
	<input type="text" name="pencarian" placeholder="Pencarian" class="form-control" autocomplete="" style="color: red; float: right;">\
	</div>\
	<table class="table table-striped table-hover table-responsive">\
	<thead class="text-info">\
	<tr>\
	<th>Pelanggan</th>\
	<th class="text-right">Total Belanja</th>\
	<th class="text-center">Produk</th>\
	</tr>\
	</thead>\
	<tbody>\
	<tr>\
	<td>Umum</td>\
	<td align="right">1200000</td>\
	<td align="center">\
	<a href="#create-penjualan" class="btn btn-xs btn-info">Lihat</a>\
	</td>\
	</tr>\
	</tbody>    \
	</table>\
	</div>'
})

export default Vue