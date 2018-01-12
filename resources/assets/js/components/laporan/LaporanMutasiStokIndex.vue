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
				<li class="active">Laporan Mutasi Stok</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shuffle</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Mutasi Stok </h4>

					<div class="row">
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>				
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
						</div>

						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitMutasiStok()"><i class="material-icons">search</i> Cari</button>
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
									<th>Satuan</th>
									<th style="text-align:right">Awal</th>
									<th style="text-align:right">Nilai Awal</th>
									<th style="text-align:right">Masuk</th>
									<th style="text-align:right">Nilai Masuk</th>
									<th style="text-align:right">Keluar</th>
									<th style="text-align:right">Nilai Keluar</th>
									<th style="text-align:right">Akhir</th>
									<th style="text-align:right">Nilai Akhir</th>
								</tr>
							</thead>
							<tbody v-if="mutasiStok.length > 0 && loading == false"  class="data-ada">
								<tr v-for="mutasiStoks, index in mutasiStok" >

									<td>{{ mutasiStoks.daftar_produks.kode_barang }}</td>
									<td>{{ mutasiStoks.daftar_produks.nama_barang }}</td>
									<td>{{ mutasiStoks.daftar_produks.nama_satuan }}</td>
									<td align="right">{{ mutasiStoks.stok_awal | pemisahTitik }}</td>
									<td align="right">{{ mutasiStoks.total_awal | pemisahTitik }}</td>
									<td align="right">{{ mutasiStoks.stok_masuk | pemisahTitik }}</td>
									<td align="right">{{ mutasiStoks.total_masuk | pemisahTitik }}</td>
									<td align="right">{{ mutasiStoks.stok_keluar | pemisahTitik }}</td>
									<td align="right">{{ mutasiStoks.total_keluar | pemisahTitik }}</td>
									<td align="right">{{ mutasiStoks.stok_akhir | pemisahTitik }}</td>
									<td align="right">{{ mutasiStoks.total_akhir | pemisahTitik }}</td>

								</tr>

								<tr style="color:red">
									<td>TOTAL</td>
									<td></td>
									<td></td>
									<td align="right">{{ subtotalMutasiStok.total_stok_awal | pemisahTitik }}</td>
									<td align="right">{{ subtotalMutasiStok.total_nilai_awal | pemisahTitik }}</td>
									<td align="right">{{ subtotalMutasiStok.total_stok_masuk | pemisahTitik }}</td>
									<td align="right">{{ subtotalMutasiStok.total_nilai_masuk | pemisahTitik }}</td>
									<td align="right">{{ subtotalMutasiStok.total_stok_keluar | pemisahTitik }}</td>
									<td align="right">{{ subtotalMutasiStok.total_nilai_keluar | pemisahTitik }}</td>
									<td align="right">{{ subtotalMutasiStok.total_stok_akhir | pemisahTitik }}</td>
									<td align="right">{{ subtotalMutasiStok.total_nilai_akhir | pemisahTitik }}</td>

								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="mutasiStok.length == 0 && loading == false">
								<tr ><td colspan="11"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
						</div><!--RESPONSIVE-->

						<!--DOWNLOAD EXCEL-->
						<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a>

						<!--CETAK LAPORAN-->
						<a href="#" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'"><i class="material-icons">print</i> Cetak Laporan</a>

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="mutasiStokData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
				</div>
			</div>
		</div>
	</div>
	

</template>


<script>
export default {
	data: function () {
		return {
			produk: [],
			mutasiStok: [],
			mutasiStokData: {},
			subtotalMutasiStok: {},
			filter: {
				dari_tanggal: '',
				sampai_tanggal: '',
            },
			url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-mutasi-stok"),
			urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-mutasi-stok/download-excel-mutasi-stok"),
			urlCetak : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-mutasi-stok/cetak-laporan"),
			pencarian: '',
			loading: false,
		}
	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
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
    	submitMutasiStok(){
    		var app = this;
    		app.prosesLaporan();
    		app.totalMutasiStok();
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
    			app.mutasiStok = resp.data.data;
    			app.mutasiStokData = resp.data;
    			app.loading = false
    			console.log(resp.data.data);
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Mutasi Stok");
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
    			app.mutasiStok = resp.data.data;
    			app.mutasiStokData = resp.data;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Mutasi Stok");
    		});
    	},
      totalMutasiStok() {
    		var app = this;	
    		var newFilter = app.filter;

    		app.loading = true,
    		axios.post(app.url+'/subtotal-mutasi-stok', newFilter)
    		.then(function (resp) {
    			app.subtotalMutasiStok = resp.data;
    			app.loading = false
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Subtotal Mutasi Stok");
    		});
    	},    	
        showButton() {
        	var app = this;
    		var filter = app.filter;

    		var date_dari_tanggal = filter.dari_tanggal;
    		var date_sampai_tanggal = filter.sampai_tanggal;
    		var dari_tanggal = "" + date_dari_tanggal.getFullYear() +'-'+ ((date_dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_dari_tanggal.getMonth() + 1) +'-'+ (date_dari_tanggal.getDate() > 9 ? '' : '0') + date_dari_tanggal.getDate();
    		var sampai_tanggal = "" + date_sampai_tanggal.getFullYear() +'-'+ ((date_sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_sampai_tanggal.getMonth() + 1) +'-'+ (date_sampai_tanggal.getDate() > 9 ? '' : '0') + date_sampai_tanggal.getDate();

    		$("#btnExcel").show();
    		$("#btnCetak").show();
    		$("#btnExcel").attr('href', app.urlDownloadExcel+'/'+dari_tanggal+'/'+sampai_tanggal);
    		$("#btnCetak").attr('href', app.urlCetak+'/'+dari_tanggal+'/'+sampai_tanggal);
    	}
    }
}
</script>