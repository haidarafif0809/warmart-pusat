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
				<li class="active">Laporan Laba Kotor Penjualan /Pelanggan</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">trending_up</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Laba Kotor Penjualan /Pelanggan </h4>

					<div class="row">
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.kas" :settings="placeholder_kas" id="kas" ref="kas"> 
								<option v-for="data_kas, index in kas" v-bind:value="data_kas.id">
									{{ data_kas.nama_kas }}
								</option>
							</selectize-component>
							<input class="form-control" type="hidden"  v-model="filter.kas"  name="kas" id="kas"  v-shortkey="['f1']" @shortkey="pilihKas()">
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.jenis_laporan" :settings="placeholder_laporan" id="jenis_laporan" ref="jenis_laporan"> 
								<option v-bind:value="0" > Laporan Detail </option>
								<option v-bind:value="1" > Laporan Rekap </option>
							</selectize-component>
							<input class="form-control" type="hidden"  v-model="filter.jenis_laporan"  name="jenis_laporan" id="jenis_laporan"  v-shortkey="['f1']" @shortkey="pilihJenisLaporan()">
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'">
							</datepicker>
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'">								
							</datepicker>
						</div>
						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitLaporan()"><i class="material-icons">search</i> Cari</button>
						</div>
					</div>

					<div class=" table-responsive">
						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian_kas_masuk" placeholder="Pencarian" class="form-control">
						</div>

						<h4><b>KAS MASUK {{subtotalLaporanKasDetail | pemisahTitik}}</b></h4>
						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th>Jenis Transaksi</th>
									<th>Ke Kas</th>
									<th style="text-align:right">Total</th>
									<th style="text-align:center">Waktu</th>
								</tr>
							</thead>
							<tbody v-if="laporanKasDetail.length > 0 && loading == false"  class="data-ada">
								<tr v-for="laporanKasDetails, index in laporanKasDetail" >

									<td>{{ laporanKasDetails.data_laporan.no_faktur }}</td>
									<td>{{ laporanKasDetails.jenis_transaksi }}</td>
									<td>{{ laporanKasDetails.data_laporan.nama_kas }}</td>
									<td align="right">{{ laporanKasDetails.data_laporan.jumlah_masuk | pemisahTitik }}</td>
									<td align="center">{{ laporanKasDetails.data_laporan.created_at | tanggal }}</td>

								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="laporanKasDetail.length == 0 && loading == false">
								<tr ><td colspan="5"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
					</div><!--RESPONSIVE-->
					<vue-simple-spinner v-if="loading"></vue-simple-spinner>
					<div align="right"><pagination :data="laporanKasDetailData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
					<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

					<!--DOWNLOAD EXCEL-->
					<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'">
						<i class="material-icons">file_download</i> Download Excel
					</a>

					<!--CETAK LAPORAN-->
					<a href="#" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'">
						<i class="material-icons">print</i> Cetak Laporan
					</a>
				</div>
			</div>
		</div>
	</div>

</template>

<script>
	export default {
		data: function () {
			return {
				kas : [],
				laporanKasDetail: [],
				laporanKasDetailData: {},
				subtotalLaporanKasDetail: '',
				totalAkhir: {},
				filter: {
					dari_tanggal: '',
					sampai_tanggal: new Date(),
					jenis_laporan: '',
					kas: '',
				},
				url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kas"),
				urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kas/download-excel"),
				urlCetak : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kas/cetak-laporan"),
				pencarian_kas_masuk: '',
				placeholder_laporan: {
					placeholder: '--JENIS LAPORAN--'
				},
				placeholder_kas: {
					placeholder: '--PILIH KAS--'
				},
				loading: false,
				loadingAkhir: false,
			}
		},
		mounted() {
			var app = this;
			var awal_tanggal = new Date();
			awal_tanggal.setDate(1);

			app.dataKas();
			app.filter.dari_tanggal = awal_tanggal;

		},
		watch: {
        // whenever question changes, this function will run
        pencarian_kas_masuk: function (newQuestion) {
        	this.getHasilPencarian();
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
    	pilihJenisLaporan(){      
    		this.$refs.jenis_laporan.$el.selectize.focus();
    	},
    	pilihKas(){      
    		this.$refs.kas.$el.selectize.focus();
    	},
    	dataKas() {
    		var app = this;
    		axios.get(app.url+'/pilih-kas').then(function (resp) {
    			app.kas = resp.data;
    			app.pilihKas();
    			app.pilihJenisLaporan();
    		})
    		.catch(function (resp) {
    			alert("Tidak Bisa Memuat Kas");
    		});
    	},
    	submitLaporan(){
    		var app = this;

    		if (app.filter.kas == "") {
    			app.alertGagal('Silakan Pilih Kas Terlebih Dahulu');
    			app.pilihKas();
    		}else if (app.filter.jenis_laporan == "") {    			
    			app.alertGagal('Silakan Pilih Jenis Laporan Terlebih Dahulu');
    			app.pilihJenisLaporan();
    		}else{
    			app.prosesLaporan();
    			app.totalLaporanKasDetail();    			
    			app.showButton();     			
    		} 

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
    			console.log(resp.data.data);
    			app.laporanKasDetail = resp.data.data;
    			app.laporanKasDetailData = resp.data;
    			app.loading = false
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Laba Kotor Penjualan /Pelanggan");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		var newFilter = app.filter;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.post(app.url+'/pencarian?search='+app.pencarian_kas_masuk+'&page='+page, newFilter)
    		.then(function (resp) {
    			console.log(resp.data.data)
    			app.laporanKasDetail = resp.data.data;
    			app.laporanKasDetailData = resp.data;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Laba Kotor Penjualan /Pelanggan");
    		});
    	},
    	totalLaporanKasDetail() {
    		var app = this;	
    		var newFilter = app.filter;

    		app.loading = true,
    		axios.get(app.url+'/subtotal-laporan-kas-detail-masuk', newFilter)
    		.then(function (resp) {
    			app.subtotalLaporanKasDetail = resp.data;
    			app.loading = false
    			console.log(resp.data)
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Subtotal Laporan Kas");
    		});
    	},
    	totalAkhirLabaKotor() {
    		var app = this;	
    		var newFilter = app.filter;

    		app.loadingAkhir = true,
    		axios.post(app.url+'/total-akhir-laba-kotor', newFilter)
    		.then(function (resp) {
    			app.totalAkhir = resp.data;
    			app.loadingAkhir = false;
    			console.log(resp.data);
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Subtotal Laba Kotor");
    		});
    	},
    	downloadExcel() {
    		var app = this;	
    		var newFilter = app.filter;
    		if (newFilter.pelanggan == "" || newFilter.pelanggan == null) {
    			newFilter.pelanggan = 0;
    		}
    		axios.get(app.urlDownloadExcel+'/'+newFilter.dari_tanggal+'/'+newFilter.sampai_tanggal+'/'+newFilter.pelanggan)
    		.then(function (resp) {
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Subtotal Laba Kotor");
    		});
    	},
    	showButton() {
    		var app =  this;
    		var filter = app.filter;

    		if (filter.pelanggan == "") {
    			filter.pelanggan = 0;
    		};

    		var date_dari_tanggal = filter.dari_tanggal;
    		var date_sampai_tanggal = filter.sampai_tanggal;
    		var dari_tanggal = "" + date_dari_tanggal.getFullYear() +'-'+ ((date_dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_dari_tanggal.getMonth() + 1) +'-'+ (date_dari_tanggal.getDate() > 9 ? '' : '0') + date_dari_tanggal.getDate();
    		var sampai_tanggal = "" + date_sampai_tanggal.getFullYear() +'-'+ ((date_sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_sampai_tanggal.getMonth() + 1) +'-'+ (date_sampai_tanggal.getDate() > 9 ? '' : '0') + date_sampai_tanggal.getDate();


    		$("#btnExcel").show();
    		$("#btnCetak").show();
    		$("#btnExcel").attr('href', app.urlDownloadExcel+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.pelanggan);
    		$("#btnCetak").attr('href', app.urlCetak+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.pelanggan);  
    	},
    	alertGagal(pesan) {
    		this.$swal({
    			title: "Peringatan!",
    			text: pesan,
    			icon: "warning",
    		});
    	},
    }
}
</script>