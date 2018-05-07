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
				<li><router-link :to="{name: 'indexPenerimaanProduk'}">Penerimaan Produk</router-link></li>
				<li class="active">Detail Penerimaan Produk</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Detail Penerimaan Produk {{no_faktur}}</h4>
					<div class="toolbar">
						<p> <router-link :to="{name: 'indexPenerimaanProduk'}" class="btn btn-primary">Kembali</router-link></p>
					</div>

					<div class="row">
						<div class="col-md-8">
							<div class=" table-responsive ">

								<div class="pencarian">
									<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
								</div>

								<table class="table table-striped table-hover" v-if="seen">
									<thead class="text-primary">
										<tr>

											<th>Produk</th>
											<th style="text-align:right;">Jumlah Order</th>
											<th style="text-align:right;">Jumlah Fisik</th>
											<th style="text-align:right;">Selisih Fisik</th>
											<th><center>Satuan</center></th>
										</tr>
									</thead>
									<tbody v-if="detailPenerimaanProduk.length"  class="data-ada">
										<tr v-for="detailPenerimaanProduk, index in detailPenerimaanProduk" >

											<td>{{ detailPenerimaanProduk.detail_penerimaan.produk.kode_barang }} - {{ detailPenerimaanProduk.nama_produk }}</td>
											<td style="text-align:right;"> {{ detailPenerimaanProduk.detail_penerimaan.jumlah_produk | pemisahTitik }}</td>
											<td style="text-align:right;"> {{ detailPenerimaanProduk.detail_penerimaan.jumlah_fisik | pemisahTitik }}</td>
											<td style="text-align:right;"> {{ detailPenerimaanProduk.detail_penerimaan.selisih_fisik | pemisahTitik }}</td>
											<td align="center">{{ detailPenerimaanProduk.detail_penerimaan.nama_satuan }}</td>
										</tr>
									</tbody>                    
									<tbody class="data-tidak-ada" v-else>
										<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
									</tbody>
								</table>    

								<vue-simple-spinner v-if="loading"></vue-simple-spinner>

								<div align="right"><pagination :data="detailPenerimaanProdukData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="card card-stats">
								<div class="card-header" data-background-color="blue">
									<i class="material-icons">shopping_cart</i>
								</div>
								<div class="card-content">
									<p class="category"><font style="font-size:20px;">Order Pembelian</font></p>
									<h3 class="card-title">
										<b>
											<font style="font-size:15px;">{{ pembelianOrder }}</font>
										</b>
									</h3>
								</div>
								<div class="card-footer">

								</div>
							</div>
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
				errors: [],
				detailPenerimaanProduk: [],
				detailPenerimaanProdukData : {},
				url : window.location.origin+(window.location.pathname).replace("dashboard", "penerimaan-produk"),
				pencarian: '',
				loading: true,
				seen : false,    
				no_faktur:'',
				id_penerimaan: '',
				pembelianOrder: ''     
			}
		},
		mounted() {   
			var app = this;
			app.id_penerimaan = app.$route.params.id;
			app.getResults();
			app.getPenerimaan(app.id_penerimaan);
		},
		watch: {
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
				axios.get(app.url+'/view-detail-penerimaan-produk/'+id+'?page='+page)
				.then(function (resp) {
					console.log(resp.data.data)
					app.detailPenerimaanProduk = resp.data.data;
					app.detailPenerimaanProdukData = resp.data;
					app.no_faktur = resp.data.no_faktur;
					app.loading = false;
					app.seen = true;
				})
				.catch(function (resp) {
					console.log(resp);
					app.loading = false;
					app.seen = true;
					alert("Tidak Dapat Memuat Detail Penerimaan Produk");
				});
			}, 
			getHasilPencarian(page){
				var app = this;
				var id = app.$route.params.id;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/pencarian-detail-penerimaan/'+id+'?search='+app.pencarian+'&page='+page)
				.then(function (resp) {
					app.detailPenerimaanProduk = resp.data.data;
					app.detailPenerimaanProdukData = resp.data;
					app.loading = false;
					app.seen = true;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Detail Penerimaan Produk");
				});
			},
			getPenerimaan(id_penerimaan){
				var app = this;

				axios.get(app.url+'/data-penerimaan/'+id_penerimaan)
				.then(function (resp) {
					app.pembelianOrder = resp.data
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Detail Penerimaan Produk");
				});
			}
		}
	}
</script>

