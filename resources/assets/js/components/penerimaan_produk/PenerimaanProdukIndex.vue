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
				<li class="active">Penerimaan Produk</li>
			</ul>


			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Penerimaan Produk </h4>

					<div class="toolbar">
						<p> <router-link :to="{name: 'createPenerimaanProduk'}" class="btn btn-primary">Tambah Penerimaan Produk</router-link></p>
					</div>


					<div class=" table-responsive ">
						<div class="pencarian">
							<input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th>Waktu</th>
									<th>Faktur Order</th>
									<th>Supplier</th>
									<th>Status</th>
									<th style="text-align:right;">Edit</th>
									<th style="text-align:right;">Detail</th>
									<th style="text-align:right;">Cetak</th>									
									<th style="text-align:right;">Delete</th>
								</tr>
							</thead>
							<tbody v-if="penerimaanProduk.length > 0 && loading == false"  class="data-ada">
								<tr v-for="penerimaanProduks, index in penerimaanProduk" >

									<td>{{ penerimaanProduks.data.no_faktur_penerimaan }}</td>
									<td>{{ penerimaanProduks.data.created_at | tanggal }}</td>
									<td>{{ penerimaanProduks.data.faktur_order }}</td>
									<td>{{ penerimaanProduks.data.nama_suplier }}</td>
									<td>{{ penerimaanProduks.status_penerimaan }}</td>

									<td style="text-align:right;">
										<router-link :to="{name: 'prosesEditPenerimaanProduk', params: {id: penerimaanProduks.data.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + penerimaanProduks.data.id" v-if="penerimaanProduks.data.status_penerimaan < 3">
											Edit 
										</router-link>

										<a href="#penerimaan-produk" class="btn btn-xs btn-default" v-bind:id="'edit-' + penerimaanProduks.data.id" v-on:click="orderTerpakai(penerimaanProduks.data.id, index,penerimaanProduks.data.no_faktur_penerimaan, 'Diedit')" v-else> Edit 
										</a>
									</td>

									<td style="text-align:right;">
										<router-link :to="{name: 'detailPenerimaanProduk', params: {id: penerimaanProduks.data.id}}" class="btn btn-xs btn-info" v-bind:id="'detail-' + penerimaanProduks.data.no_faktur_penerimaan" >
											Detail
										</router-link> 
									</td>
									<td style="text-align:right;">
										<a target="blank" class="btn btn-primary btn-xs" v-bind:href="'penerimaan-produk/cetak-besar-penerimaan-produk/'+penerimaanProduks.data.id">Cetak Ulang</a>
									</td>
									<td style="text-align:right;"> 
										<a  href="#penerimaan-produk" class="btn btn-xs btn-danger" v-bind:id="'delete-' + penerimaanProduks.data.id" v-on:click="deleteEntry(penerimaanProduks.data.id, index,penerimaanProduks.data.no_faktur_penerimaan)" v-if="penerimaanProduks.data.status_penerimaan < 3">Delete</a>

										<a href="#penerimaan-produk" class="btn btn-xs btn-danger" v-bind:id="'delete-' + penerimaanProduks.data.id" v-on:click="orderTerpakai(penerimaanProduks.data.id, index,penerimaanProduks.data.no_faktur_penerimaan, 'Dihapus')" v-else> Delete 
										</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="penerimaanProduk.length == 0 && loading == false">
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="penerimaanProdukData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
				penerimaanProduk: [],
				penerimaanProdukData: {},
				otoritas: {},
				detailPenerimaanProduks: [],
				url : window.location.origin+(window.location.pathname).replace("dashboard", "penerimaan-produk"),
				pencarian: '',
				loading: true,
				no_faktur : 0,
				kas : '',
				total : 0,
				potongan : 0,
				tunai : 0,
				kembalian : 0,
				jatuh_tempo : '',
				user_buat : '',
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
		filters: {	
			pemisahTitik: function (value) {
				var angka = [value];
				var numberFormat = new Intl.NumberFormat('es-ES');
				var formatted = angka.map(numberFormat.format);
				return formatted.join('; ');
			},				
			tanggal: function (value) {
				return moment(String(value)).format('DD/MM/YYYY hh:mm')
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
					console.log(resp.data.data)
					app.penerimaanProduk = resp.data.data;
					app.penerimaanProdukData = resp.data;
					app.loading = false;
				})
				.catch(function (resp) {
					console.log(resp);
					app.loading = false;
					alert("Tidak Dapat Memuat Penerimaan Produk");
				});
			},
			getHasilPencarian(page){
				var app = this;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
				.then(function (resp) {
					app.penerimaanProduk = resp.data.data;
					app.penerimaanProdukData = resp.data;
					app.loading = false;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Penerimaan Produk");
				});
			},
			deleteEntry(id, index,no_faktur) {
				this.$swal({
					title: "Konfirmasi Hapus",
					text : "Anda Yakin Ingin Menghapus Faktur "+no_faktur+" ?",
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
								app.$swal('Oops...','Penerimaan Produk Tidak Dapat Dihapus, Karena Sudah Terpakai','error');
								app.loading = false;

							}else{
								app.getResults();
								app.alert("Menghapus Penerimaan Produk Faktur "+no_faktur);
								app.loading = false;  
							}  
						})
						.catch(function (resp) {
							alert("Tidak dapat Menghapus Penerimaan Produk");
						});
					}else {
						app.$swal.close();
					}
				});
			},
			detailModalPembelian(id,index,no_faktur){
				var app = this;
				axios.get(app.url+'/detail-view?id='+id+'&no_faktur='+no_faktur)
				.then(function (resp) {
					app.detailPenerimaanProduks = resp.data.data;
				})
				.catch(function (resp) {
					app.loading = false;
					alert("Tidak Dapat Memuat Detail Penerimaan Produk");
				});
			},
			orderTerpakai(id, index,no_faktur_penerimaan, aksi) {
				var app = this;                  
				app.alertGagal(`Faktur ${no_faktur_penerimaan} Tidak Bisa ${aksi} Karena Sudah Terpakai`)
			},
			alert(pesan) {
				this.$swal({
					title: "Berhasil ",
					text: pesan,
					icon: "success",
					timer: 1000,
				});
			},
			alertGagal(pesan) {
				this.$swal({
					title: "Perhatian !",
					text: pesan,
					icon: "warning",
					timer: 2000,
				});
			},
		}
	}
</script>

