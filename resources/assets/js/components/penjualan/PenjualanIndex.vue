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
				<li class="active">Laporan Penjualan</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Laporan Penjualan </h4>
					<div class="toolbar">
					</div>

					<div class=" table-responsive ">

						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
						</div>

						<table class="table table-striped table-hover" v-if="seen">
							<thead class="text-primary">
								<tr>

									<th>No Transaksi</th>
									<th>Waktu</th>
									<th>Pelanggan</th>
									<th>Status</th>
									<th>Total</th>
									<th>Detail</th>
									<th>Edit</th>
									<th>Hapus</th>

								</tr>
							</thead>
							<tbody v-if="penjualan.length"  class="data-ada">
								<tr v-for="penjualan, index in penjualan" >

									<td>{{ penjualan.id }}</td>
									<td>{{ penjualan.waktu }}</td>
									<td>{{ penjualan.pelanggan }}</td>
									<td>{{ penjualan.status_penjualan }}</td>
									<td> {{ new Intl.NumberFormat().format(penjualan.total) }},00</td>
									<td>
										<router-link :to="{name: 'detailPenjualan', params: {id: penjualan.id}}" class="btn btn-xs btn-info" v-bind:id="'detail-' + penjualan.id" >
										Detail </router-link> 
									</td>
									<td><router-link :to="{name: 'prosesEditPenjualan', params: {id: penjualan.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + penjualan.id" >
									Edit </router-link> </td> 
									<td><a href="#penjualan" class="btn btn-xs btn-danger" v-bind:id="'delete-' + penjualan.id" v-on:click="deleteEntry(penjualan.id, index,penjualan.nama_produk,penjualan.subtotal)">Delete</a></td>
								</tr>
							</tbody>                    
							<tbody class="data-tidak-ada" v-else>
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>    

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="penjualanData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
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
			penjualan: [],
			penjualanData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
			pencarian: '',
			loading: true,
			seen : false,           
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
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/view?page='+page)
		.then(function (resp) {
			app.penjualan = resp.data.data;
			app.penjualanData = resp.data;
			app.loading = false;
			app.seen = true;
		})
		.catch(function (resp) {
			console.log(resp);
			app.loading = false;
			app.seen = true;
			alert("Tidak Dapat Memuat Penjualan");
		});
	}, 
	getHasilPencarian(page){
		var app = this;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
		.then(function (resp) {
			app.penjualan = resp.data.data;
			app.penjualanData = resp.data;
			app.loading = false;
			app.seen = true;
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Penjualan");
		});
	},    
	deleteEntry(id, index,no_faktur) {

		var app = this;
		app.$swal({
			text: "Anda Yakin Ingin Menghapus Penjualan "+no_faktur+ " ?",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {

				this.prosesDelete(id,no_faktur);

			} else {

				app.$swal.close();

			}
		});

	},
	prosesDelete(id,no_faktur){

		var app = this;
		app.loading = true;
		axios.delete(app.url+'/' + id)
		.then(function (resp) {

			if (resp.data == 0) {

				app.alertTbs("Penjualan "+no_faktur+" Gagal Dihapus!")
				app.loading = false

			}else{
				
				app.getResults()
				app.alert("Menghapus Penjualan "+no_faktur)
				app.loading = false
			}


		})
		.catch(function (resp) {

			console.log(resp);
			app.loading = false;
			alert("Tidak dapat Menghapus Penjualan "+no_faktur);
		});
	},
	alert(pesan) {
		this.$swal({
			title: "Berhasil ",
			text: pesan,
			icon: "success",
		});
	}
}
}
</script>

