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
						<p> <router-link :to="{name: 'createSettingPromo'}" class="btn btn-primary">Tambah Setting Promo</router-link></p>
					</div>

					<div class=" table-responsive ">

						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
						</div>

						<table class="table table-striped table-hover" v-if="seen">
							<thead class="text-primary">
								<tr>

									<th>Nama Produk</th>
									<th>Harga Coret</th>
									<th>Baner Promo</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody v-if="settingPromo.length"  class="data-ada">
								<tr v-for="settingPromos, index in settingPromo" >

									<td>{{ settingPromos.settingpromo.barang.kode_barang }} || {{ settingPromos.settingpromo.barang.nama_barang }}</td>
									<td> {{ settingPromos.settingpromo.harga_coret }}</td>
									<td> {{ settingPromos.settingpromo.baner_promo }}</td>
									<td> 
									<router-link :to="{name: 'editBank', params: {id: settingPromos.settingpromo.id_setting_promo }}" class="btn btn-xs btn-default" v-bind:id="'edit-' + settingPromos.settingpromo.id_setting_promo" >
									Edit 
									</router-link> <a href="#"
									class="btn btn-xs btn-danger" v-bind:id="'delete-' + settingPromos.settingpromo.id_setting_promo"
									v-on:click="deleteEntry(settingPromos.settingpromo.id_setting_promo, index,settingPromos.settingpromo.barang.nama_barang)">
									Delete
									</a></td>
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
			url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-promo"),
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
methods: {
	getResults(page) {
		var app = this; 
		var id = app.$route.params.id;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/view?page='+page)
		.then(function (resp) {
			app.settingPromo = resp.data.data;
			app.settingPromoData = resp.data;
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
		var id = app.$route.params.id;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/pencarian-detail-user-topos/'+id+'?search='+app.pencarian+'&page='+page)
		.then(function (resp) {
			app.settingPromo = resp.data.data;
			app.settingPromoData = resp.data;
			app.loading = false;
			app.seen = true;
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Detail User Topos");
		});
	}
}
}
</script>

