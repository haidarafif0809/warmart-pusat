<style scoped>
	.pencarian {
		color: red; 
		float: right;
	}
	.table>thead>tr>th {
		border-bottom-width: 1px;
		font-size: 1em;
		font-weight: 300;
	}
	.table>tbody>tr>td {
		border-bottom-width: 1px;
		font-size: 0.9em;
		font-weight: 300;
		color: black;
	}
</style>

<template>
	<div class="row">
		<div class="col-md-12">

			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Retur Pembelian</li>
			</ul>

			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Retur Pembelian </h4>
					
					<div class="toolbar">
						<p> <router-link :to="{name: 'createReturPembelian'}" class="btn btn-primary">Tambah Retur Pembelian</router-link></p>
					</div>

					<div class=" table-responsive ">
						<div class="pencarian">
							<input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th>Supplier</th>
									<th style="text-align:right;">Total Retur</th>
									<th style="text-align:right;">Potong Hutang</th>
									<th style="text-align:right;">Kas</th>
									<th style="text-align:right;">Diskon</th>
									<th style="text-align:right;">Pajak</th>
									<th style="text-align:center;">Waktu</th>									
								</tr>
							</thead>
							<tbody v-if="returPembelian.length > 0 && loading == false"  class="data-ada">
								<tr v-for="returPembelians, index in returPembelian" >
									<td>{{ returPembelians.waktu }}</td>
									<td>{{ returPembelians.waktu }}</td>
									<td>{{ returPembelians.waktu }}</td>
									<td>{{ returPembelians.waktu }}</td>
									<td>{{ returPembelians.waktu }}</td>
									<td>{{ returPembelians.waktu }}</td>
									<td>{{ returPembelians.waktu }}</td>
									<td>{{ returPembelians.waktu }}</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="returPembelian.length == 0 && loading == false">
								<tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="returPembelianData" v-on:pagination-change-page="getResults" :limit="7"></pagination>
						</div>

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
				returPembelian: [],
				returPembelianData: {},
				url : window.location.origin+(window.location.pathname).replace("dashboard", "pembayaran-hutang"),
				pencarian: '',
				loading: true,
			}
		},
		mounted() {
			var app = this;
			app.getResults();
		},
		watch: {
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
					app.returPembelian = resp.data.data;
					app.returPembelianData = resp.data;
					app.loading = false;
				})
				.catch(function (resp) {
					console.log(resp);
					app.loading = false;
					alert("Tidak Dapat Memuat Retur Pembelian");
				});
			},
			getHasilPencarian(page){
				var app = this;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
				.then(function (resp) {
					app.returPembelian = resp.data.data;
					app.returPembelianData = resp.data;
					app.loading = false;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Retur Pembelian");
				});
			},
			alert(pesan) {
				this.$swal({
					title: "Berhasil ",
					text: pesan,
					icon: "success",
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
								app.$swal('Oops...','Retur Pembelian Tidak Dapat Dihapus, Karena Sudah Terpakai','error');
								app.loading = false;

							}else{
								app.getResults();
								app.alert("Menghapus Pembelian "+no_faktur);
								app.loading = false;  
							}
						})
						.catch(function (resp) {
							alert("Tidak dapat Menghapus Pembelian");
						});
					}else {
						app.$swal.close();
					}
				});
			}
		}
	}
</script>

