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
				<li><router-link :to="{name: 'indexPembelianOrder'}">Order Pembelian</router-link></li>
				<li class="active">Detail Order Pembelian</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Detail Order Pembelian {{no_faktur}}</h4>
					<div class="toolbar">
						<p> <router-link :to="{name: 'indexPembelianOrder'}" class="btn btn-primary">Kembali</router-link></p>
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
											<th style="text-align:right;">Jumlah</th>
											<th>Satuan</th>
											<th style="text-align:right;">Harga</th>
											<th style="text-align:right;">Potongan</th>
											<th style="text-align:right;">Tax</th>
											<th style="text-align:right;">Subtotal</th>

										</tr>
									</thead>
									<tbody v-if="detailOrderPembelian.length"  class="data-ada">
										<tr v-for="detailOrderPembelian, index in detailOrderPembelian" >

											<td>{{ detailOrderPembelian.detail_order.produk.kode_barang }} - {{ detailOrderPembelian.nama_produk }}</td>
											<td style="text-align:right;"> {{ detailOrderPembelian.detail_order.jumlah_produk | pemisahTitik }}</td>
											<td>{{ detailOrderPembelian.detail_order.nama_satuan }}</td>
											<td style="text-align:right;"> {{ detailOrderPembelian.detail_order.harga_produk | pemisahTitik }}</td>
											<td style="text-align:right;">{{ detailOrderPembelian.detail_order.potongan | pemisahTitik }}</td>
											<td style="text-align:right;"> {{ detailOrderPembelian.detail_order.tax }}</td>
											<td style="text-align:right;"> {{ detailOrderPembelian.detail_order.subtotal | pemisahTitik }} </td>
										</tr>
									</tbody>                    
									<tbody class="data-tidak-ada" v-else>
										<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
									</tbody>
								</table>    

								<vue-simple-spinner v-if="loading"></vue-simple-spinner>

								<div align="right"><pagination :data="detailOrderPembelianData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
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
				detailOrderPembelian: [],
				detailOrderPembelianData : {},
				url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian-order"),
				separator: {
					decimal: ',',
					thousands: '.',
					prefix: '',
					suffix: '',
					precision: 2,
					masked: false
				},
				pencarian: '',
				loading: true,
				seen : false,    
				subtotal : 0,
				no_faktur:'',         
			}
		},
		mounted() {   
			var app = this;
			app.getResults();
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
			}
		},
		methods: {
			getResults(page) {
				var app = this; 
				var id = app.$route.params.id;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/view-detail-order-pembelian/'+id+'?page='+page)
				.then(function (resp) {
					console.log(resp.data.data)
					app.detailOrderPembelian = resp.data.data;
					app.detailOrderPembelianData = resp.data;
					app.no_faktur = resp.data.no_faktur;
					app.loading = false;
					app.seen = true;
					if (app.subtotal == 0) {
						$.each(resp.data.data, function (i, item) {
							app.subtotal += parseFloat(resp.data.data[i].detail_order.subtotal)
						});
					}
				})
				.catch(function (resp) {
					console.log(resp);
					app.loading = false;
					app.seen = true;
					alert("Tidak Dapat Memuat Detail Order Pembelian");
				});
			}, 
			getHasilPencarian(page){
				var app = this;
				var id = app.$route.params.id;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/pencarian-detail-pembelian/'+id+'?search='+app.pencarian+'&page='+page)
				.then(function (resp) {
					app.detailOrderPembelian = resp.data.data;
					app.detailOrderPembelianData = resp.data;
					app.loading = false;
					app.seen = true;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Detail Order Pembelian");
				});
			}
		}
	}
</script>

