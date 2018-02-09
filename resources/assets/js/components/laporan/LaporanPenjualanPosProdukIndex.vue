<style scoped>
.pencarian {
	color: red; 
	float: right;
	padding-bottom: 10px;
}
</style>

<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Laporan Penjualan /Produk</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">library_books</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Penjualan Pos /Produk </h4>

					<div class="row">
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>				
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.produk" :settings="placeholder_produk" id="pilih_produk"> 
								<option v-for="produks, index in produk" v-bind:value="produks.id" >{{ produks.nama_produk }}</option>
							</selectize-component>
						</div>

						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitPenjualanProduk()"><i class="material-icons">search</i> Cari</button>
						</div>
					</div>

					<div class=" table-responsive">
						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control">
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>Kode Produk</th>
									<th>Nama Produk</th>
									<th style="text-align:right">Jumlah</th>
									<th>Satuan</th>
									<th style="text-align:right">Subtotal</th>
									<th style="text-align:right">Diskon</th>
									<th style="text-align:right">Pajak</th>
									<th style="text-align:right">Total</th>
								</tr>
							</thead>
							<tbody v-if="penjualanProduk.length > 0 && loading == false"  class="data-ada">
								<tr v-for="penjualanProduks, index in penjualanProduk" >

									<td>{{ penjualanProduks.laporan_penjualans.kode_barang }}</td>
									<td>{{ penjualanProduks.laporan_penjualans.nama_barang }}</td>
									<td align="right">{{ penjualanProduks.laporan_penjualans.jumlah_produk | pemisahTitik }}</td>
									<td>{{ penjualanProduks.laporan_penjualans.nama_satuan }}</td>
									<td align="right">{{ penjualanProduks.laporan_penjualans.subtotal | pemisahTitik }}</td>
									<td align="right">{{ penjualanProduks.laporan_penjualans.potongan | pemisahTitik }}</td>
									<td align="right">{{ penjualanProduks.laporan_penjualans.tax | pemisahTitik }}</td>
									<td align="right">{{ penjualanProduks.sub_total | pemisahTitik }}</td>

								</tr>

								<tr style="color:red">
									<td>TOTAL</td>
									<td></td>
									<td align="right">{{ totalPenjualanPosProduk.jumlah_produk | pemisahTitik }}</td>
									<td></td>
									<td align="right">{{ totalPenjualanPosProduk.subtotal | pemisahTitik }}</td>
									<td></td>
									<td></td>
									<td align="right">{{ totalPenjualanPosProduk.total | pemisahTitik }}</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="penjualanProduk.length == 0 && loading == false">
								<tr ><td colspan="9"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
					</div><!--RESPONSIVE-->

					<vue-simple-spinner v-if="loading"></vue-simple-spinner>
					<div align="right"><pagination :data="penjualanProdukData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
				</div>
				<hr>
				<!-- laporan penjualan online per peroduk -->
				<div class="card-content">
					<h4 class="card-title"> Laporan Penjualan Online /Produk </h4>

					<div class=" table-responsive">
						<div class="pencarian">
							<input type="text" name="pencarianOnline" v-model="pencarianOnline" placeholder="Pencarian" class="form-control">
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>Kode Produk</th>
									<th>Nama Produk</th>
									<th style="text-align:right">Harga</th>
									<th style="text-align:right">Jumlah</th>
									<th style="text-align:right">Subtotal</th>
									<th style="text-align:right">Diskon</th>
									<th style="text-align:right">Total</th>
								</tr>
							</thead>
							<tbody v-if="penjualanOnlineProduk.length > 0 && loading == false"  class="data-ada">
								<tr v-for="penjualanOnlineProduks, index in penjualanOnlineProduk" >

									<td>{{ penjualanOnlineProduks.laporan_penjualan_online.kode_barang }}</td>
									<td>{{ penjualanOnlineProduks.laporan_penjualan_online.nama_barang }}</td>
									<td align="right">{{ penjualanOnlineProduks.laporan_penjualan_online.harga | pemisahTitik }}</td>
									<td align="right">{{ penjualanOnlineProduks.laporan_penjualan_online.jumlah | pemisahTitik }}</td>
									<td align="right">{{ penjualanOnlineProduks.laporan_penjualan_online.total | pemisahTitik }}</td>
									<td align="right">{{ penjualanOnlineProduks.laporan_penjualan_online.potongan | pemisahTitik }}</td>
									<td align="right">{{ penjualanOnlineProduks.laporan_penjualan_online.subtotal | pemisahTitik }}</td>

								</tr>

								<tr style="color:red">
									<td>TOTAL</td>
									<td></td>
									<td></td>
									<td align="right">{{ totalPenjualanOnlineProduk.jumlah | pemisahTitik }}</td>
									<td align="right">{{ totalPenjualanOnlineProduk.total | pemisahTitik }}</td>
									<td></td>
									<td align="right">{{ totalPenjualanOnlineProduk.subtotal | pemisahTitik }}</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="penjualanOnlineProduk.length == 0 && loading == false">
								<tr ><td colspan="9"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
					</div><!--RESPONSIVE-->

					<hr>
					<!--DOWNLOAD EXCEL-->
					<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a>

					<!--CETAK LAPORAN-->
					<a href="#" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'"><i class="material-icons">print</i> Cetak Laporan</a>

					<vue-simple-spinner v-if="loading"></vue-simple-spinner>
					<div align="right"><pagination :data="penjualanOnlineProdukData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
export default {
	data: function () {
		return {
			produk: [],
			penjualanProduk: [],
			penjualanOnlineProduk:[],
			penjualanProdukData: {},
			penjualanOnlineProdukData: {},
			totalPenjualanPosProduk: {},
			totalPenjualanOnlineProduk: {},
			filter: {
				dari_tanggal: '',
				sampai_tanggal: new Date(),
				produk: '',
			},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-penjualan-pos-produk"),
			urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-penjualan-pos-produk/download-excel-penjualan-pos-produk"),
			urlCetak : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-penjualan-pos-produk/cetak-laporan"),
			pencarian: '',
			pencarianOnline: '',
			loading: false,
			placeholder_produk: {
				placeholder: '--PILIH PRODUK--'
			},
			placeholder_pelangan: {
				placeholder: '--PILIH PELANGGAN--'
			},
		}
	},
	mounted() {
		var app = this;
		var awal_tanggal = new Date();
		awal_tanggal.setDate(1);

		app.dataProduk();
		app.filter.dari_tanggal = awal_tanggal;
	},
	watch: {
// whenever question changes, this function will run
pencarian: function (newQuestion) {
	this.getHasilPencarian();
},
pencarianOnline: function (newQuestion) {
	this.getHasilPencarianOnline();
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
	submitPenjualanProduk(){
		var app = this;
		var filter = app.filter;
		app.prosesLaporan();
		app.prosesLaporanOnline();
		app.totalPenjualanProduk();
		app.totalPenjualanOnlineProduks();
		app.showButton();   		
	},
	prosesLaporan(page) {
		var app = this;	
		var newFilter = app.filter;
		if (typeof page === 'undefined') {
			page = 1;
		}
		app.loading = true,
		axios.post(app.url+'/view?page='+page, newFilter)
		.then(function (resp) {
			app.penjualanProduk = resp.data.data;
			app.penjualanProdukData = resp.data;
			app.loading = false
			// console.log(resp.data.data);
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Laporan Penjualan Pos /Produk");
		});
	},prosesLaporanOnline(page) {
		var app = this;	
		var newFilter = app.filter;
		if (typeof page === 'undefined') {
			page = 1;
		}
		app.loading = true,
		axios.post(app.url+'/view-online?page='+page, newFilter)
		.then(function (resp) {
			app.penjualanOnlineProduk = resp.data.data;
			app.penjualanOnlineProdukData = resp.data;
			app.loading = false
			console.log(resp.data.data);
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Laporan Penjualan Online /Produk");
		});
	},
	
	dataProduk() {
		var app = this;
		axios.get(app.url+'/pilih-produk')
		.then(function (resp) {
			app.produk = resp.data;
			console.log(resp.data)
		})
		.catch(function (resp) {
			alert("Tidak bisa memuat produk ");
		});
	},
	getHasilPencarian(page){
		var app = this;
		var newFilter = app.filter;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.post(app.url+'/pencarian?search='+app.pencarian+'&page='+page, newFilter)
		.then(function (resp) {
			app.penjualanProduk = resp.data.data;
			app.penjualanProdukData = resp.data;
		})
		.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Laporan Penjualan Pos /Produk");
		});
	},
	getHasilPencarianOnline(page){
		var app = this;
		var newFilter = app.filter;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.post(app.url+'/pencarian-online?search='+app.pencarianOnline+'&page='+page, newFilter)
		.then(function (resp) {
			app.penjualanOnlineProduk = resp.data.data;
			app.penjualanOnlineProdukData = resp.data;
		})
		.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Laporan Penjualan Pos /Produk");
		});
	},
	totalPenjualanProduk() {
		var app = this;	
		var newFilter = app.filter;

		app.loading = true,
		axios.post(app.url+'/total-penjualan-pos-produk', newFilter)
		.then(function (resp) {
			app.totalPenjualanPosProduk = resp.data;
			app.loading = false
			console.log(resp.data);    			
		})
		.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Subtotal Mutasi Stok");
		});
	},	totalPenjualanOnlineProduks() {
		var app = this;	
		var newFilter = app.filter;

		app.loading = true,
		axios.post(app.url+'/total-penjualan-online-produk', newFilter)
		.then(function (resp) {
			app.totalPenjualanOnlineProduk = resp.data;
			app.loading = false
			console.log(resp.data);    			
		})
		.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Total Penjualan Online /Produk");
		});
	},	
	showButton() {
		var app = this;
		var filter = app.filter;

		if (filter.produk == "") {
			filter.produk = 0;
		};

		var date_dari_tanggal = filter.dari_tanggal;
		var date_sampai_tanggal = filter.sampai_tanggal;
		var dari_tanggal = "" + date_dari_tanggal.getFullYear() +'-'+ ((date_dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_dari_tanggal.getMonth() + 1) +'-'+ (date_dari_tanggal.getDate() > 9 ? '' : '0') + date_dari_tanggal.getDate();
		var sampai_tanggal = "" + date_sampai_tanggal.getFullYear() +'-'+ ((date_sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_sampai_tanggal.getMonth() + 1) +'-'+ (date_sampai_tanggal.getDate() > 9 ? '' : '0') + date_sampai_tanggal.getDate();

		$("#btnExcel").show();
		$("#btnCetak").show();
		$("#btnExcel").attr('href', app.urlDownloadExcel+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.produk);
		$("#btnCetak").attr('href', app.urlCetak+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.produk);
	}
}
}
</script>