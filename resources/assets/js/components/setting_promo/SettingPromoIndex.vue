<style scoped>
.pencarian {
	color: red; 
	float: right;
}
</style>

<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Setting Promo</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">settings_applications</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Setting Promo</h4>
					<div class="toolbar">
						<router-link :to="{name: 'createSettingPromo'}" class="btn btn-primary" v-if="otoritas.tambah_setting_promo == 1">Tambah Setting Promo</router-link>
					</div>

					<div class=" table-responsive ">

						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
						</div>

						<table class="table table-striped table-hover" v-if="seen">
							<thead class="text-primary">
								<tr>

									<th>Nama Produk</th>
									<th style="text-align:right"> Harga Promo </th>
									<th style="text-align:right">Periode Promo</th>
									<th style="text-align:right">Baner Promo</th>
									<th style="text-align:right">Status</th>
									<th style="text-align:right" v-if="otoritas.edit_setting_promo == 1 || otoritas.hapus_setting_promo == 1">Aksi</th>
								</tr>
							</thead>
							<tbody v-if="settingPromo.length"  class="data-ada">
								<tr v-for="settingPromos, index in settingPromo" >

									<td>{{ settingPromos.settingpromo.kode_barang }} || {{ settingPromos.settingpromo.nama_barang }}</td>
									<td align="right"> {{ settingPromos.settingpromo.harga_coret | pemisahTitik}}</td>
									<td align="right"> {{ settingPromos.settingpromo.dari_tanggal | tanggal }} sd {{ settingPromos.settingpromo.sampai_tanggal | tanggal }}</td>
									<td align="right"><a v-if="settingPromos.settingpromo.baner_promo != undefined" v-bind:href="url_baner_promo+ '/'+settingPromos.settingpromo.baner_promo" target="blank">Lihat Baner</a>
										<p v-else >Tidak Ada Baner</p>
									</td>
									<td align="right" v-if="settingPromos.settingpromo.status == 1"> Aktif </td>
									<td align="right" v-else> Tidak Aktif </td>
									<td align="right"> 
										<router-link :to="{name: 'editSettingPromo', params: {id: settingPromos.settingpromo.id_setting_promo }}" class="btn btn-xs btn-default" v-bind:id="'edit-' + settingPromos.settingpromo.id_setting_promo" v-if="otoritas.edit_setting_promo == 1">
											Edit 
										</router-link> 
										<a href="#"
										class="btn btn-xs btn-danger" v-bind:id="'delete-' + settingPromos.settingpromo.id_setting_promo"
										v-on:click="deleteEntry(settingPromos.settingpromo.id_setting_promo, index,settingPromos.settingpromo.nama_barang)" v-if="otoritas.hapus_setting_promo == 1">
										Delete
									</a>
								</td>
							</tr>
						</tbody>                    
						<tbody class="data-tidak-ada" v-else>
							<tr ><td colspan="5"  class="text-center">Tidak Ada Data</td></tr>
						</tbody>
					</table>    

					<vue-simple-spinner v-if="loading"></vue-simple-spinner>

					<div align="right"><pagination :data="settingPromoData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
				</div>



			</div>
		</div>
	</div>
</div>

</template>


<script>
export default {
	data: function () {
		return {
			errors: [],
			settingPromo: [],
			settingPromoData : {},
			otoritas: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-promo"),
			url_baner_promo : window.location.origin+(window.location.pathname).replace("dashboard", "baner_setting_promo"),
			pencarian: '',
			loading: true,
			seen : false        
		}
	},
	mounted() {   
		var app = this;
		app.getResults();
	},
	watch: {
// whenever question changes, this function will run
pencarian: function (newQuestion) {
	this.getHasilPencarian()
	this.loading = true
}
},
filters: {
	pemisahTitik: function (value) {
		var angka = [value];
		var numberFormat = new Intl.NumberFormat('es-ES');
		var formatted = angka.map(numberFormat.format);
		return formatted.join('; ');
	},
	tanggal: function (value) {
		return moment(String(value)).format('DD/MM/YYYY')
	}
},
methods: {
	getResults(page) {
		var app = this; 
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/view?page='+page)
		.then(function (resp) {
			app.settingPromo = resp.data.data;
			app.settingPromoData = resp.data;
			app.otoritas = resp.data.otoritas.original;
			app.loading = false;
			app.seen = true;
		})
		.catch(function (resp) {
			console.log(resp);
			app.loading = false;
			app.seen = true;
			alert("Tidak Dapat Memuat Detail User Topos");
		});
	}, 
	getHasilPencarian(page){
		var app = this;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
		.then(function (resp) {
			app.settingPromo = resp.data.data;
			app.settingPromoData = resp.data;
			app.otoritas = resp.data.otoritas.original;
			app.loading = false;
			app.seen = true;
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Data Promo");
		});
	},
	alert(pesan) {
		this.$swal({
			title: "Berhasil Menghapus Promo!",
			text: pesan,
			icon: "success",
		});
	},
	deleteEntry(id, index,nama_barang) {
		this.$swal({
			title: "Konfirmasi Hapus",
			text : "Yakin Ingin Menghapus Promo "+nama_barang+" ?",
			icon : "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				var app = this;
				axios.delete(app.url+'/' + id)
				.then(function (resp) {
					app.getResults();
					app.alert();            
				})
				.catch(function (resp) {
					swal("Gagal Menghapus Promo", {
						icon: "warning",
					});
				});
			}
			this.$router.replace('/setting-promo/');
		});

	}

}
}
</script>

