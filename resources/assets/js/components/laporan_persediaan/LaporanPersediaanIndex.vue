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
					<li class="active">Laporan Persediaan</li>
				</ul>
				<div class="card">
					<div class="card-header card-header-icon" data-background-color="purple">
						<i class="material-icons">storage</i>
					</div>
					<div class="card-content">
						<h4 class="card-title"> Laporan Persediaan </h4>
						<div class="toolbar">
						</div>

						<div class=" table-responsive ">

							<div class="col-md-2 col-xs-12">
								<div class="panel panel-default">
									<button id="btnFilter" class="btn btn-info collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										<i class="material-icons">date_range</i> Filter Tanggal
									</button>
								</div>
							</div>

							<div class="col-md-12 col-xs-12">
								<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
									<div class="panel-body">
										<div class="row">
											<div class="form-group col-md-2">
												<datepicker :input-class="'form-control'" placeholder="Tanggal" v-model="filter.tanggal" name="tanggal" v-bind:id="'dari_tanggal'">													
												</datepicker>             
											</div>

											<div class="col-md-2">
												<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="filterPeriode()"><i class="material-icons">search</i> Cari</button>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="" v-if="seenFilter == false">
								<input type="text" name="pencarian" v-model="pencarianFilter" placeholder="Pencarian" class="form-control pencarian" autocomplete="" v-else>
							</div>

							
							<table class="table table-striped table-hover" v-if="seen">
								<thead class="text-primary">
									<tr>

										<th>Kode Produk</th>
										<th>Nama Produk</th>
										<th>Satuan</th>
										<th class="text-right">Stok</th>
										<th class="text-right">Hpp</th>
										<th class="text-right">Nilai</th>

									</tr>
								</thead>
								<tbody v-if="lap_persediaan.length"  class="data-ada">
									<tr v-for="lap_persediaan, index in lap_persediaan" >

										<td>{{ lap_persediaan.kode_produk }}</td>
										<td>{{ lap_persediaan.nama_produk }}</td>
										<td>{{ lap_persediaan.satuan }}</td>
										<td align="right">{{ lap_persediaan.stok }}</td>
										<td align="right">{{ lap_persediaan.hpp }}</td>
										<td align="right">{{ lap_persediaan.nilai }}</td>
									</tr>
									<tr><td style="color:red">Total Nilai</td>
										<td></td>
										<td></td>
										<td align="right"></td>
										<td align="right"></td>
										<td style="color:red" align="right">{{totalNilai}}</td>
									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else>
									<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>	


							<vue-simple-spinner v-if="loading"></vue-simple-spinner>

							<div align="right" v-if="seenFilter == false"><pagination :data="lapPersediaanData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
							<div align="right" v-else><pagination :data="lapPersediaanData" v-on:pagination-change-page="filterPeriode" :limit="4"></pagination></div>
						</div>

						<!--DOWNLOAD EXCEL-->
						<a href="laporan-kartu-stok/download-excel-kartu-stok" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a>
						<!--CETAK LAPORAN-->
						<a href="laporan-kartu-stok/cetak-laporan" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'"><i class="material-icons">print</i> Cetak Laporan</a>

					</div>
				</div>
			</div>
		</div>


	</template>


	<script>
		export default {
			data: function () {
				return {
					lap_persediaan: [],
					lapPersediaanData: {},
					totalNilai: '',
					url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-persediaan"),
					urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-persediaan/download-excel-persediaan"),
					urlCetak : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-persediaan/cetak-laporan"),
					pencarian: '',
					pencarianFilter: '',
					loading: true,
					seen : false,
					seenFilter : false,
					filter: {
						tanggal: new Date(),
					},
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
			mounted() {
				var app = this;
				app.getResults();
				app.showButton();
			},
			watch: {
				pencarian: function (newQuestion) {
					this.getHasilPencarian();
					this.loading = true;  
				},
				pencarianFilter: function (newQuestion) {
					this.pencarianFilterPeriode();
					this.loading = true;  
				}
			},

			methods: {
				tanggal(filter){
					var tanggal = "" + filter.tanggal.getFullYear() +'-'+ ((filter.tanggal.getMonth() + 1) > 9 ? '' : '0') + (filter.tanggal.getMonth() + 1) +'-'+ (filter.tanggal.getDate() > 9 ? '' : '0') + filter.tanggal.getDate();

					return tanggal;
				},
				getResults(page) {
					var app = this;	
					if (typeof page === 'undefined') {
						page = 1;
					}
					axios.get(app.url+'/view?page='+page)
					.then(function (resp) {
						app.lap_persediaan = resp.data.data;
						app.lapPersediaanData = resp.data;
						app.totalNilai = resp.data.totalnilai;
						app.loading = false;
						app.seen = true;
						app.seenFilter = false;
					})
					.catch(function (resp) {
						console.log(resp);
						app.loading = false;
						app.seen = true;
						app.seenFilter = false;
						alert("Tidak Dapat Memuat Laporan Persediaan");
					});
				},
				getHasilPencarian(page){
					var app = this;
					app.loading = true;
					app.seen = false;
					if (typeof page === 'undefined') {
						page = 1;
					}
					axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
					.then(function (resp) {
						app.lap_persediaan = resp.data.data;
						app.lapPersediaanData = resp.data;
						app.loading = false;
						app.seen = true;
						app.seenFilter = false;
					})
					.catch(function (resp) {
						console.log(resp);
						alert("Tidak Dapat Memuat Laporan Persediaan");
					});
				},
				showButton() {
					var app = this;
					$("#btnExcel").show();
					$("#btnCetak").show();
					$("#btnExcel").attr('href', app.urlDownloadExcel);
					$("#btnCetak").attr('href', app.urlCetak);
				},
				filterPeriode(page){
					var app = this;
					var newFilter = app.filter;
					var tanggal = app.tanggal(newFilter);
					if (typeof page === 'undefined') {
						page = 1;
					}
					axios.post(app.url+'/view-pertanggal?page='+page, newFilter)
					.then(function (resp) {
						app.lap_persediaan = resp.data.data;
						app.lapPersediaanData = resp.data;
						app.totalNilai = resp.data.totalnilai;
						app.loading = false;
						app.seen = true;
						app.seenFilter = true;
						$("#btnExcel").attr('href', app.urlDownloadExcel+'-tanggal/'+tanggal);
						$("#btnCetak").attr('href', app.urlCetak+'-tanggal/'+tanggal);
					})
					.catch(function (resp) {
						console.log(resp);
						app.loading = false;
						app.seen = true;
						app.seenFilter = true;
						alert("Tidak Dapat Memuat Laporan Persediaan");
					});
				},
				pencarianFilterPeriode(page){
					var app = this;
					var newFilter = app.filter;
					if (typeof page === 'undefined') {
						page = 1;
					}
					axios.post(app.url+'/pencarian-pertanggal?search='+app.pencarianFilter+'&page='+page, newFilter)
					.then(function (resp) {
						app.lap_persediaan = resp.data.data;
						app.lapPersediaanData = resp.data;
						app.totalNilai = resp.data.totalnilai;
						app.loading = false;
						app.seen = true;
						app.seenFilter = true;
					})
					.catch(function (resp) {
						console.log(resp);
						app.loading = false;
						app.seen = true;
						app.seenFilter = true;
						alert("Tidak Dapat Memuat Laporan Persediaan");
					});
				},
			}
		}
	</script>