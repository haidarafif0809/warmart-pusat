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
				<li><router-link :to="{name: 'indexReturPembelian'}">Retur Pembelian</router-link></li>
				<li class="active">Detail Retur Pembelian</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Detail Retur Pembelian {{no_faktur}}</h4>
					<div class="toolbar">
						<p> <router-link :to="{name: 'indexReturPembelian'}" class="btn btn-primary">Kembali</router-link></p>
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
											<th style="text-align:right;">Jumlah Retur</th>
											<th style="text-align:center;">Satuan</th>
											<th style="text-align:right;">Harga</th>
											<th style="text-align:right;">Potongan</th>
											<th style="text-align:right;">Tax</th>
											<th style="text-align:right;">Subtotal</th>
										</tr>
									</thead>
									<tbody v-if="detailReturPembelian.length"  class="data-ada">
										<tr v-for="detailReturPembelian, index in detailReturPembelian" >

											<td>{{ detailReturPembelian.detail_retur.nama_barang | capitalize }}</td>
											<td style="text-align:right;"> {{ detailReturPembelian.detail_retur.jumlah_produk | pemisahTitik }}</td>
											<td>{{ detailReturPembelian.detail_retur.nama_satuan }}</td>
											<td style="text-align:right;"> {{ detailReturPembelian.detail_retur.harga_produk | pemisahTitik }}</td>
											<td style="text-align:right;">{{ detailReturPembelian.detail_retur.potongan | pemisahTitik }}</td>
											<td style="text-align:right;"> {{ detailReturPembelian.detail_retur.tax }}</td>
											<td style="text-align:right;"> {{ detailReturPembelian.detail_retur.subtotal | pemisahTitik }} </td>
										</tr>
									</tbody>                    
									<tbody class="data-tidak-ada" v-else>
										<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
									</tbody>
								</table>    

								<vue-simple-spinner v-if="loading"></vue-simple-spinner>

								<div align="right"><pagination :data="detailReturPembelianData" v-on:pagination-change-page="getDetail" :limit="4"></pagination></div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="card card-stats">
								<div class="card-header" data-background-color="blue">
									<i class="material-icons">shopping_cart</i>
								</div>
								<div class="card-content">
									<p class="category"><font style="font-size:20px;">Subtotal</font></p>
									<h3 class="card-title">
										<b>
											<font style="font-size:32px;">{{ subtotal | pemisahTitik }}</font>
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
				detailReturPembelian: [],
				detailReturPembelianData : {},
				url : window.location.origin+(window.location.pathname).replace("dashboard", "retur-pembelian"),
				pencarian: '',
				loading: true,
				seen : false,    
				subtotal : 0,
				no_faktur:'',         
			}
		},
		mounted() {   
			var app = this;
			app.getDetail();
		},
		watch: {
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
			capitalize: function (value) {
				return value.replace(/(^|\s)\S/g, l => l.toUpperCase())
			},
		},
		methods: {
			getDetail(page) {
				var app = this; 
				var id = app.$route.params.id;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/view-detail/'+id+'?page='+page)
				.then(function (resp) {
					console.log(resp.data.data)
					app.detailReturPembelian = resp.data.data;
					app.detailReturPembelianData = resp.data;
					app.no_faktur = resp.data.no_faktur;
					app.loading = false;
					app.seen = true;
					if (app.subtotal == 0) {
						$.each(resp.data.data, function (i, item) {
							app.subtotal += parseFloat(resp.data.data[i].detail_retur.subtotal)
						});
					}
				})
				.catch(function (resp) {
					console.log(resp);
					app.loading = false;
					app.seen = true;
					alert("Tidak Dapat Memuat Detail Retur Pembelian");
				});
			}, 
			getHasilPencarian(page){
				var app = this;
				var id = app.$route.params.id;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/pencarian-detail/'+id+'?search='+app.pencarian+'&page='+page)
				.then(function (resp) {
					app.detailReturPembelian = resp.data.data;
					app.detailReturPembelianData = resp.data;
					app.loading = false;
					app.seen = true;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Detail Retur Pembelian");
				});
			}
		}
	}
</script>

