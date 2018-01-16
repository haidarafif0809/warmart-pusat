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
				<li class="active">Laporan Kartu Stok</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">content_paste</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Kartu Stok </h4>

					<div class="row">
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.produk" :settings="placeholder_produk" id="pilih_produk"> 
                            	<option v-for="produks, index in produk" v-bind:value="produks.id" >{{ produks.nama_produk }}</option>
                            </selectize-component>
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>				
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
						</div>

						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitKartuStok()"><i class="material-icons">search</i> Cari</button>
						</div>
					</div>

					<div class=" table-responsive">
						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control">
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No Faktur</th>
									<th>Jenis Transaksi</th>
									<th style="text-align:right">Harga</th>
									<th style="text-align:center">Waktu</th>
									<th style="text-align:right">Jumlah Masuk</th>
									<th style="text-align:right">Jumlah Keluar</th>
									<th style="text-align:right">Saldo</th>
								</tr>
							</thead>

								<tr style="color:red" v-if="subtotalSaldoAwal.status == 200">

									<td></td>
									<td>SALDO AWAL</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td align="right">{{ subtotalSaldoAwal.data | pemisahTitik }}</td>

								</tr>

							<tbody v-if="kartuStok.length > 0 && loading == false"  class="data-ada">

								<tr v-for="kartuStoks, index in kartuStok" >

									<td>{{ kartuStoks.data_kartu_stoks.no_faktur }}</td>
									<td>{{ kartuStoks.data_kartu_stoks.jenis_transaksi }}</td>
									<td align="right">{{ kartuStoks.data_kartu_stoks.harga_unit | pemisahTitik }}</td>
									<td align="center">{{ kartuStoks.data_kartu_stoks.created_at | tanggal }}</td>
									<td align="right">{{ kartuStoks.data_kartu_stoks.jumlah_masuk | pemisahTitik }}</td>
									<td align="right">{{ kartuStoks.data_kartu_stoks.jumlah_keluar | pemisahTitik }}</td>
									<td align="right">{{ kartuStoks.saldo_awal | pemisahTitik }}</td>

								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="kartuStok.length == 0 && loading == false">
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
						</div><!--RESPONSIVE-->

						<!--DOWNLOAD EXCEL-->
						<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a>

						<!--CETAK LAPORAN-->
						<a href="#" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'"><i class="material-icons">print</i> Cetak Laporan</a>

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="kartuStokData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
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
			kartuStok: [],
			kartuStokData: {},
			subtotalSaldoAwal: {},
			filter: {
				produk: '',
				dari_tanggal: '',
				sampai_tanggal: '',
            },
			url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kartu-stok"),
			urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kartu-stok/download-excel-kartu-stok"),
			urlCetak : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kartu-stok/cetak-laporan"),
			pencarian: '',
			loading: false,
	        placeholder_produk: {
	        	placeholder: '--PILIH PRODUK--'
	        }
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
	  },
	  tanggal: function (value) {
	    return moment(String(value)).format('DD/MM/YYYY hh:mm')
	  }
	},
	mounted() {
		var app = this;
		app.dataProduk();
	},
    methods: {
        dataProduk() {
	        var app = this;
	        axios.get(app.url+'/pilih-produk')
	        .then(function (resp) {
	        	app.produk = resp.data;
	        })
	        .catch(function (resp) {
	          	alert("Tidak Bisa Memuat Produk ");
	        });
      	},
    	submitKartuStok(){
    		var app = this;
    		app.prosesLaporan();
    		app.totalSaldoAwal();
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
    			app.kartuStok = resp.data.data;
    			app.kartuStokData = resp.data;
    			app.loading = false
    			console.log(resp.data.data);
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Kartu Stok");
    		});
    	},
      	totalSaldoAwal() {
    		var app = this;	
    		var newFilter = app.filter;

    		app.loading = true,
    		axios.post(app.url+'/total-saldo-awal', newFilter)
    		.then(function (resp) {
    			app.subtotalSaldoAwal = resp;
    			app.loading = false
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Subtotal Saldo Awal");
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
    			console.log(resp);
    			app.kartuStok = resp.data.data;
    			app.kartuStokData = resp.data;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Kartu Stok");
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