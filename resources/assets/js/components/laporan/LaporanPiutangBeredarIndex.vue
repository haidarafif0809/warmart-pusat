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
				<li class="active">Laporan Piutang</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">library_books</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Piutang</h4>

					<div class="row">
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>				
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.pelanggan" id="pilih_pelanggan" :settings="placeholder_pelanggan"> 
								<option v-for="pelanggans, index in pelanggan" v-bind:value="pelanggans.id" >{{ pelanggans.nama_pelanggan }}</option>
							</selectize-component>
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.pilih_laporan" id="pilih_laporan" ref='pilih_laporan'> 
								<option v-bind:value="1">Beredar</option>
								<option v-bind:value="2">Terbayar</option>
							</selectize-component>
						</div>
						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitPiutangBeredar()"><i class="material-icons">search</i> Cari</button>
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
									<th>Pelanggan</th>
									<th style="text-align:right">Nilai Transaksi</th>
									<th style="text-align:right">Dibayar</th>
									<th style="text-align:right">Nilai Piutang</th>
									<th style="text-align:right">Jatuh Tempo</th>
									<th style="text-align:right">Umur Piutang</th>
									<th style="text-align:right">Petugas</th>
								</tr>
							</thead>
							<tbody v-if="piutangBeredar.length > 0 && loading == false"  class="data-ada">
								<tr v-for="piutangBeredars, index in piutangBeredar" >
									<td>{{ piutangBeredars.waktu }}</td>
									<td>{{ piutangBeredars.no_faktur }}</td>
									<td>{{ piutangBeredars.pelanggan }}</td>
									<td align="right">{{ piutangBeredars.nilai_transaksi | pemisahTitik }}</td>
									<td align="right">{{ piutangBeredars.pembayaran | pemisahTitik }}</td>
									<td align="right">{{ piutangBeredars.sisa_piutang | pemisahTitik }}</td>
									<td align="right">{{ piutangBeredars.jatuh_tempo | tanggal }}</td>
									<td align="right">{{ piutangBeredars.umur_piutang | pemisahTitik }} Hari</td>
									<td align="right">{{ piutangBeredars.petugas }}</td>
								</tr>

								<tr style="color:red">
									<td>TOTAL</td>
									<td></td>
									<td></td>
									<td align="right">{{ dataTotalPiutangBeredar.nilai_transaksi | pemisahTitik }}</td>
									<td align="right">{{ dataTotalPiutangBeredar.pembayaran | pemisahTitik }}</td>
									<td align="right">{{ dataTotalPiutangBeredar.sisa_piutang | pemisahTitik }}</td>
									<td align="right"></td>
									<td align="right"></td>
									<td align="right"></td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="piutangBeredar.length == 0 && loading == false">
								<tr ><td colspan="9"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>

					</div><!--RESPONSIVE-->

					<!--DOWNLOAD EXCEL-->
					<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a>

					<!--CETAK LAPORAN-->
					<a href="#" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'"><i class="material-icons">print</i> Cetak Laporan</a>

					<vue-simple-spinner v-if="loading"></vue-simple-spinner>
					<div align="right"><pagination :data="piutangBeredarData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
				</div>

				<hr>


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
			piutangBeredar: [],
			piutangBeredarData: {},
			dataTotalPiutangBeredar: {},
			filter: {
				dari_tanggal: '',
				sampai_tanggal: new Date(),
				pelanggan: '',
				pilih_laporan : 1,
			},
			placeholder_pelanggan: {
				selected: ''
			},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-piutang-beredar"), 
			urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-piutang-beredar/download-excel-piutang-beredar"), 
			urlCetak : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-piutang-beredar/cetak-laporan"), 
			pencarian: '',
			loading: false,
		}
	},
	mounted() {
		var app = this;
		var awal_tanggal = new Date();
		awal_tanggal.setDate(1);
		app.$store.dispatch('LOAD_PELANGGAN_LIST');
		app.filter.dari_tanggal = awal_tanggal;
	},
	computed : mapState ({    
		pelanggan(){
			return this.$store.state.pelanggan
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
		},
		tanggal: function (value) {
			return moment(String(value)).format('DD-MM-YYYY')
		}
	},
	methods: {
		submitPiutangBeredar(){
			var app = this;
			var filter = app.filter;
			
			app.prosesLaporan();
			app.totalPiutangBeredar();
			app.showButton();	
		},
		prosesLaporan(page) {
			var app = this;	
			var newFilter = app.filter;

			if (typeof 0 === 'undefined') {
				page = 1;
			}

			app.loading = true,
			axios.post(app.url+'/view?page='+page, newFilter)
			.then(function (resp) {
				app.piutangBeredar = resp.data.data;
				app.piutangBeredarData = resp.data;
				app.loading = false
				console.log(resp.data.data);
			})
			.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Laporan Piutang");
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
				app.piutangBeredar = resp.data.data;
				app.piutangBeredarData = resp.data;
			})
			.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Laporan Piutang");
		});
		},
		totalPiutangBeredar() {
			var app = this;	
			var newFilter = app.filter;
			app.loading = true,
			axios.post(app.url+'/total-piutang-beredar', newFilter)
			.then(function (resp) {
				app.dataTotalPiutangBeredar = resp.data;
				app.loading = false
				console.log(resp.data);    			
			})
			.catch(function (resp) {
			// console.log(resp);
			alert("Tidak Dapat Memuat Total Piutang ");
		});
		},
		showButton() {
			var app = this;
			var filter = app.filter;

			if (filter.pelanggan == "") { 
				var pelanggan = "semua"; 
			}else{
				var pelanggan = filter.pelanggan; 
			}; 
			var date_dari_tanggal = filter.dari_tanggal;
			var date_sampai_tanggal = filter.sampai_tanggal;
			var dari_tanggal = "" + date_dari_tanggal.getFullYear() +'-'+ ((date_dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_dari_tanggal.getMonth() + 1) +'-'+ (date_dari_tanggal.getDate() > 9 ? '' : '0') + date_dari_tanggal.getDate();
			var sampai_tanggal = "" + date_sampai_tanggal.getFullYear() +'-'+ ((date_sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_sampai_tanggal.getMonth() + 1) +'-'+ (date_sampai_tanggal.getDate() > 9 ? '' : '0') + date_sampai_tanggal.getDate();

			$("#btnExcel").show();
			$("#btnCetak").show();
			$("#btnExcel").attr('href', app.urlDownloadExcel+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+pelanggan+'/'+filter.pilih_laporan);
			$("#btnCetak").attr('href', app.urlCetak+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+pelanggan+'/'+filter.pilih_laporan);
		}
	}
}
</script>