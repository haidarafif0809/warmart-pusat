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
				<li><router-link :to="{name: 'listPendaftaranTopos'}">User Topos</router-link></li>
				<li class="active">Detail User Topos</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">account_circle</i>
					<i class="material-icons">store</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Detail  User Topos</h4>
					<div class="toolbar">
						<p> <router-link :to="{name: 'listPendaftaranTopos'}" class="btn btn-primary">Kembali</router-link></p>
					</div>

					<div class=" table-responsive ">

						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
						</div>

						<table class="table table-striped table-hover" v-if="seen">
							<thead class="text-primary">
								<tr>

									<th>Nama Toko</th>
									<th>Lama Berlanggan</th>
									<th>Berlaku Hingga</th>
									<th>Transfer Ke</th>
									<th class="text-right">Harga Perbulan</th>
									<th class="text-right">Total</th>

								</tr>
							</thead>
							<tbody v-if="userTopos.length"  class="data-ada">
								<tr v-for="userTopos, index in userTopos" >

									<td>{{ userTopos.pendaftar_topos.name }}</td>

									<td v-if="userTopos.pendaftar_topos.lama_berlangganan == 1">1 Bulan</td>
									<td v-else-if="userTopos.pendaftar_topos.lama_berlangganan == 2">6 Bulan</td>
									<td v-else-if="userTopos.pendaftar_topos.lama_berlangganan == 3">12 Bulan</td>
									
									<td> {{ userTopos.pendaftar_topos.berlaku_hingga }}</td>
									<td> {{ userTopos.pendaftar_topos.bank.nama_bank }} | {{ userTopos.pendaftar_topos.no_rekening }} | {{ userTopos.pendaftar_topos.atas_nama }}</td>

									<td  align="right" v-if="userTopos.pendaftar_topos.lama_berlangganan == 1">500.000</td>
									<td  align="right" v-else-if="userTopos.pendaftar_topos.lama_berlangganan == 2">300.000</td>
									<td  align="right" v-else-if="userTopos.pendaftar_topos.lama_berlangganan == 3">200.000</td>

									<td align="right"> {{ new Intl.NumberFormat().format(userTopos.pendaftar_topos.total) }}</td>

								</tr>
							</tbody>                    
							<tbody class="data-tidak-ada" v-else>
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>    

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="userToposData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
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
			userTopos: [],
			userToposData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "daftar-topos"),
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
		axios.get(app.url+'/view-detail-user-topos/'+id+'?page='+page)
		.then(function (resp) {
			app.userTopos = resp.data.data;
			app.userToposData = resp.data;
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
			app.userTopos = resp.data.data;
			app.userToposData = resp.data;
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

