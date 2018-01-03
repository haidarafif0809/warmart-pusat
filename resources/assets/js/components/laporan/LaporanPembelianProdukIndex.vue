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
				<li class="active">Laporan Pembelian /Produk</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">library_books</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Pembelian /Produk </h4>

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
							<selectize-component v-model="filter.produk" :settings="placeholder_produk" id="pilih_produk"> 
                            	<option v-for="produks, index in produk" v-bind:value="produks.id" >{{ produks.nama_produk }}</option>
                            </selectize-component>
						</div>

						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitPembelianProduk()"><i class="material-icons">search</i> Cari</button>
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
									<th>Supplier</th>
									<th style="text-align:right">Jumlah</th>
									<th>Satuan</th>
									<th style="text-align:right">Diskon</th>
									<th style="text-align:right">Pajak</th>
									<th style="text-align:right">Total</th>
								</tr>
							</thead>
							<tbody v-if="pembelianProduk.length > 0 && loading == false"  class="data-ada">
								<tr v-for="pembelianProduks, index in pembelianProduk" >

									<td>{{ pembelianProduks.laporan_pembelians.kode_barang }}</td>
									<td>{{ pembelianProduks.laporan_pembelians.nama_barang }}</td>
									<td>{{ pembelianProduks.laporan_pembelians.nama_suplier }}</td>
									<td align="right">{{ pembelianProduks.laporan_pembelians.jumlah_produk }}</td>
									<td>{{ pembelianProduks.laporan_pembelians.nama_satuan }}</td>
									<td align="right">{{ pembelianProduks.laporan_pembelians.potongan }}</td>
									<td align="right">{{ pembelianProduks.laporan_pembelians.tax }}</td>
									<td align="right">{{ pembelianProduks.laporan_pembelians.subtotal }}</td>

								</tr>

								<tr style="color:red">
									<td>TOTAL</td>
									<td></td>
									<td></td>
									<td align="right">{{ subtotalPembelianProduk.jumlah_produk | pemisahTitik }}</td>
									<td></td>
									<td align="right">{{ subtotalPembelianProduk.potongan | pemisahTitik }}</td>
									<td align="right">{{ subtotalPembelianProduk.pajak | pemisahTitik }}</td>
									<td align="right">{{ subtotalPembelianProduk.subtotal | pemisahTitik }}</td>

								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="pembelianProduk.length == 0 && loading == false">
								<tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
						</div><!--RESPONSIVE-->

						<!--DOWNLOAD EXCEL-->

						<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a>

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="pembelianProdukData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
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
			suplier: [],
			pembelianProduk: [],
			pembelianProdukData: {},
			subtotalPembelianProduk: {},
			filter: {
				dari_tanggal: '',
				sampai_tanggal: '',
				produk: '',
				suplier: '',
            },
			url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-pembelian-produk"),
			urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-pembelian-produk/download-excel-pembelian-produk"),
			pencarian: '',
			loading: false,
            placeholder_produk: {
                placeholder: '--PILIH PRODUK--'
            },
            placeholder_suplier: {
                placeholder: '--PILIH SUPPLIER--'
            },
		}
	},
	mounted() {
		var app = this;
		app.dataProduk();
		app.dataSuplier();
	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        }
    },
    filters: {
	  pemisahTitik: function (value) {
	    return new Intl.NumberFormat().format(value)
	  }
	},
    methods: {
    	submitPembelianProduk(){
    		var app = this;
    		var filter = app.filter;
    		app.prosesLaporan();
    		app.totalPembelianProduk();

    		if (filter.produk == "") {
    			filter.produk = 0;
    		};
    		if (filter.suplier == "") {
    			filter.suplier = 0;
    		};

    		$("#btnExcel").show();
    		$("#btnExcel").attr('href', app.urlDownloadExcel+'/'+filter.dari_tanggal+'/'+filter.sampai_tanggal+'/'+filter.produk+'/'+filter.suplier);    		
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
    			app.pembelianProduk = resp.data.data;
    			app.pembelianProdukData = resp.data;
    			app.loading = false
    			console.log(resp.data.data);
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Pembelian /Produk");
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
    			app.pembelianProduk = resp.data.data;
    			app.pembelianProdukData = resp.data;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Pembelian /Produk");
    		});
    	},
      totalPembelianProduk() {
    		var app = this;	
    		var newFilter = app.filter;

    		app.loading = true,
    		axios.post(app.url+'/subtotal-pembelian-produk', newFilter)
    		.then(function (resp) {
    			app.subtotalPembelianProduk = resp.data;
    			app.loading = false
    			console.log(resp.data);    			
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Subtotal Mutasi Stok");
    		});
    	},
        dataProduk() {
          var app = this;
          axios.get(app.url+'/pilih-produk')
          .then(function (resp) {
            app.produk = resp.data;

        })
          .catch(function (resp) {
            alert("Tidak bisa memuat produk ");
        });
      },
      dataSuplier() {
          var app = this;
          axios.get(app.url+'/pilih-supplier')
          .then(function (resp) {
            app.suplier = resp.data;

        })
          .catch(function (resp) {
            alert("Tidak bisa memuat suplier ");
        });
      },
    }
}
</script>