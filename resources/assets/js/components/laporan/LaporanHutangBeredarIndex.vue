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
				<li class="active">Laporan Hutang Beredar</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">library_books</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Hutang Beredar </h4>

					<div class="row">
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>				
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.suplier" :settings="placeholder_suplier" id="pilih_suplier"> 
								<option v-for="supliers, index in suplier" v-bind:value="supliers.id" >{{ supliers.nama_suplier }}</option>
							</selectize-component>
						</div>
						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitHutangBeredar()"><i class="material-icons">search</i> Cari</button>
						</div>
					</div>

					<div class=" table-responsive">
						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control">
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>Waktu</th>
									<th>No Transaksi</th>
									<th>Supplier</th>
									<th style="text-align:right">Nilai Transaksi</th>
									<th style="text-align:right" >Potongan</th>
									<th style="text-align:right">Dibayar</th>
									<th style="text-align:right">Nilai Hutang</th>
									<th style="text-align:right">Jatuh Tempo</th>
									<th style="text-align:right">Petugas</th>
								</tr>
							</thead>
							<tbody v-if="hutangBeredar.length > 0 && loading == false"  class="data-ada">
								<tr v-for="hutangBeredars, index in hutangBeredar" >
								</tr>

								<tr style="color:red">
									<td>TOTAL</td>
									<td></td>
									<td></td>
									<td align="right"></td>
									<td align="right"></td>
									<td align="right"></td>
									<td align="right"></td>
									<td align="right"></td>
									<td align="right"></td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="hutangBeredar.length == 0 && loading == false">
								<tr ><td colspan="9"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
					</div><!--RESPONSIVE-->

					<vue-simple-spinner v-if="loading"></vue-simple-spinner>
					<div align="right"><pagination :data="hutangBeredarData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
				</div>

					<hr>
					<!--DOWNLOAD EXCEL-->
					<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a>

					<!--CETAK LAPORAN-->
					<a href="#" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'"><i class="material-icons">print</i> Cetak Laporan</a>

				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
import { mapState } from 'vuex';
export default {
	data: function () {
		return {
			hutangBeredar: [],
			hutangBeredarData: {},
			filter: {
				dari_tanggal: '',
				sampai_tanggal: new Date(),
				suplier: '',
			},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-hutang-beredar"),
			urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-hutang-beredar/download-excel-hutang-beredar"),
			urlCetak : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-hutang-beredar/cetak-laporan"),
			pencarian: '',
			pencarianOnline: '',
			loading: false,
		}
	},
	mounted() {
		var app = this;
		var awal_tanggal = new Date();
		awal_tanggal.setDate(1);
		app.$store.dispatch('LOAD_SUPLIER_LIST');
		app.filter.dari_tanggal = awal_tanggal;
	},
	computed : mapState ({    
       suplier(){
        return this.$store.state.suplier
      }
    }),
	watch: {
		// whenever question changes, this function will run
		pencarian: function (newQuestion) {
			this.getHasilPencarian();
		},
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
		submitHutangBeredar(){
			var app = this;
			var filter = app.filter;
			app.prosesLaporan();
			app.totalHutangBeredar();
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
			app.hutangBeredar = resp.data.data;
			app.hutangBeredarData = resp.data;
			app.loading = false
			console.log(resp.data.data);
		})
		.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Laporan Penjualan Pos /pelanggan");
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
			app.hutangBeredar = resp.data.data;
			app.hutangBeredarData = resp.data;
		})
		.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Laporan Penjualan Pos /pelanggan");
		});
	},
	totalHutangBeredar() {
		var app = this;	
		var newFilter = app.filter;

		app.loading = true,
		axios.post(app.url+'/total-penjualan-pos-pelanggan', newFilter)
		.then(function (resp) {
			app.totalPenjualanPosPelanggan = resp.data;
			app.loading = false
			console.log(resp.data);    			
		})
		.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Subtotal Mutasi Stok");
		});
	},	
	showButton() {
		var app = this;
		var filter = app.filter;

		if (filter.pelanggan == "") {
			filter.pelanggan = "semua";
		};
		if (filter.kasir == "") {
			filter.kasir = 0;
		};

		var date_dari_tanggal = filter.dari_tanggal;
		var date_sampai_tanggal = filter.sampai_tanggal;
		var dari_tanggal = "" + date_dari_tanggal.getFullYear() +'-'+ ((date_dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_dari_tanggal.getMonth() + 1) +'-'+ (date_dari_tanggal.getDate() > 9 ? '' : '0') + date_dari_tanggal.getDate();
		var sampai_tanggal = "" + date_sampai_tanggal.getFullYear() +'-'+ ((date_sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_sampai_tanggal.getMonth() + 1) +'-'+ (date_sampai_tanggal.getDate() > 9 ? '' : '0') + date_sampai_tanggal.getDate();

		$("#btnExcel").show();
		$("#btnCetak").show();
		$("#btnExcel").attr('href', app.urlDownloadExcel+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.pelanggan+'/'+filter.kasir);
		$("#btnCetak").attr('href', app.urlCetak+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.pelanggan+'/'+filter.kasir);
	}
}
}
</script>