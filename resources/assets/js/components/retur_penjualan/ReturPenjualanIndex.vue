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
				<li class="active">Retur Penjualan</li>
			</ul>


			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">local_atm</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Retur Penjualan </h4>

					<div class="toolbar">
						<p> <router-link :to="{name: 'createReturPenjualan'}" class="btn btn-primary" >Tambah Retur Penjualan </router-link></p>
					</div>

					<div class=" table-responsive ">
						<div class="pencarian">
							<input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th style="text-align:right;">Total</th>
									<th style="text-align:right;">Kas</th>
									<th style="text-align:right;">Waktu</th>									
									<th style="text-align:right;">Keterangan</th>
									<th style="text-align:right;">Cetak</th>
									<th style="text-align:right;">Edit</th>	
									<th style="text-align:right;">Delete</th>
								</tr>
							</thead>
							<tbody v-if="returPenjualan.length > 0 && loading == false"  class="data-ada">
								<tr v-for="returPenjualans, index in returPenjualan" >

									<td>
										<router-link :to="{name: 'detailReturPenjualan', params: {id: returPenjualans.id}}" v-bind:id="'detail-' + returPenjualans.id" >
											{{returPenjualans.no_faktur}}
										</router-link>
									</td>
									<td style="text-align:right;">{{ returPenjualans.total }}</td>
									<td style="text-align:right;">{{ returPenjualans.kas }}</td>
									<td style="text-align:right;">{{ returPenjualans.waktu }}</td>
									<td style="text-align:right;">{{ returPenjualans.keterangan }}</td>
									<td style="text-align:right;">
										<a target="blank" class="btn btn-primary btn-xs" v-bind:href="'retur-penjualan/cetak-retur-penjualan/'+returPenjualans.id">Cetak Ulang</a>
									</td>

									<td  style="text-align:right;">
										<router-link :to="{name: 'prosesEditReturPenjualan', params: {id: returPenjualans.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + returPenjualans.id" >
											Edit
										</router-link>
									</td>

									<td style="text-align:right;"> 
										<a  href="#retur-penjualan" class="btn btn-xs btn-danger" v-bind:id="'delete-' + returPenjualans.id" v-on:click="deleteEntry(returPenjualans.id, index,returPenjualans.no_faktur)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="returPenjualan.length == 0 && loading == false">
								<tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="returPenjualanData" v-on:pagination-change-page="getResults" :limit="7"></pagination></div>

					</div>
					<p style="color: red; font-style: italic;">*Note : Klik Kolom No Transaksi, Untuk Melihat Detail Transaksi Retur Penjualan .</p> 
				</div>
			</div>

		</div>
	</div>

</template>


<script>
export default {
	data: function () {
		return {
			returPenjualan: [],
			returPenjualanData: {},
			otoritas: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "retur-penjualan"),
			pencarian: '',
			loading: true,
		}
	},
	mounted() {
		var app = this;
		app.getResults();
	},
	watch: {
		// whenever question changes, this function will run
		pencarian: function (newQuestion) {
			this.getHasilPencarian();
			this.loading = true;  
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
			app.returPenjualan = resp.data.data;
			app.returPenjualanData = resp.data;
			app.loading = false;
		})
		.catch(function (resp) {
			console.log(resp);
			app.loading = false;
			alert("Tidak Dapat Memuat Retur Penjualan");
		});
	},
	getHasilPencarian(page){
		var app = this;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
		.then(function (resp) {
			app.returPenjualan = resp.data.data;
			app.returPenjualanData = resp.data;
			app.loading = false;
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Retur Penjualan");
		});
	},
	alert(pesan) {
		this.$swal({
			title: "Berhasil ",
			text: pesan,
			icon: "success",
			buttons: false,
			timer: 1000
		});
	},
	deleteEntry(id, index,no_faktur) {
		this.$swal({
			title: "Konfirmasi Hapus",
			text : "Anda Yakin Ingin Menghapus "+no_faktur+" ?",
			icon : "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				var app = this;
				app.loading = true;
				axios.delete(app.url+'/' + id)
				.then(function (resp) {
					if (resp.data == 0) {
						app.$swal('Oops...','Retur Penjualan Gagal Terhapus','error');
						app.loading = false;
					}else{
						app.getResults();
						app.alert("Menghapus Retur Penjualan "+no_faktur);
						app.loading = false;
					}
				})
				.catch(function (resp) {
					alert("Tidak dapat Menghapus Retur Penjualan");
				});
			}else {
				app.$swal.close();
			}
		});
	}
}
}
</script>

