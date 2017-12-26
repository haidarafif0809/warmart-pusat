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
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>				
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.pelanggan" :settings="placeholder_pelanggan" id="pilih_pelanggan"> 
                            	<option v-for="pelanggans, index in pelanggan" v-bind:value="pelanggans.id" >{{ pelanggans.name }}</option>
                            </selectize-component>
						</div>
						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitLabaKotor()"><i class="material-icons">search</i> Cari</button>
						</div>
					</div>

					<div class=" table-responsive">
						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian_pos" placeholder="Pencarian" class="form-control">
						</div>

						<h4><b>PENJUALAN POS</b></h4>
						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th>Waktu</th>
									<th>Pelanggan</th>
									<th style="text-align:right">Penjualan</th>
									<th style="text-align:right">Hpp</th>
									<th style="text-align:right">Laba Kotor</th>
									<th style="text-align:right">Diskon Faktur</th>
									<th style="text-align:right">Laba Jual</th>
								</tr>
							</thead>
							<tbody v-if="labaKotor.length > 0 && loading == false"  class="data-ada">
								<tr v-for="labaKotors, index in labaKotor" >

									<td>{{ labaKotors.laba_kotor.id }}</td>
									<td>{{ labaKotors.laba_kotor.created_at | tanggal }}</td>
									<td>{{ labaKotors.laba_kotor.name }}</td>
									<td align="right">{{ labaKotors.total | pemisahTitik }}</td>
									<td align="right">{{ labaKotors.hpp | pemisahTitik }}</td>
									<td align="right">{{ labaKotors.total_laba_kotor | pemisahTitik }}</td>
									<td align="right">{{ labaKotors.laba_kotor.potongan | pemisahTitik }}</td>
									<td align="right">{{ labaKotors.laba_jual | pemisahTitik }}</td>

								</tr>

								<tr style="color:red">

									<td>TOTAL</td>
									<td></td>
									<td></td>
									<td align="right">{{ subtotalLabaKotor.subtotal_penjualan | pemisahTitik }}</td>
									<td align="right">{{ subtotalLabaKotor.subtotal_hpp | pemisahTitik }}</td>
									<td align="right">{{ subtotalLabaKotor.subtotal_laba_kotor | pemisahTitik }}</td>
									<td align="right">{{ subtotalLabaKotor.subtotal_potongan | pemisahTitik }}</td>
									<td align="right">{{ subtotalLabaKotor.subtotal_laba_jual | pemisahTitik }}</td>

								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="labaKotor.length == 0 && loading == false">
								<tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
						</div><!--RESPONSIVE-->
						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="labaKotorData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
						<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

					<div class=" table-responsive">
						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian_pesanan" placeholder="Pencarian" class="form-control">
						</div>

						<h4><b>PENJUALAN ONLINE</b></h4>
						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th>Waktu</th>
									<th>Pelanggan</th>
									<th style="text-align:right">Penjualan</th>
									<th style="text-align:right">Hpp</th>
									<th style="text-align:right">Laba Kotor</th>
									<th style="text-align:right">Diskon Faktur</th>
									<th style="text-align:right">Laba Jual</th>
								</tr>
							</thead>
							<tbody v-if="labaKotorPesanan.length > 0 && loadingPesanan == false"  class="data-ada">
								<tr v-for="labaKotorPesanans, index in labaKotorPesanan" >

									<td>{{ labaKotorPesanans.laba_kotor.id }}</td>
									<td>{{ labaKotorPesanans.laba_kotor.created_at | tanggal }}</td>
									<td>{{ labaKotorPesanans.laba_kotor.name }}</td>
									<td align="right">{{ labaKotorPesanans.total | pemisahTitik }}</td>
									<td align="right">{{ labaKotorPesanans.hpp | pemisahTitik }}</td>
									<td align="right">{{ labaKotorPesanans.total_laba_kotor | pemisahTitik }}</td>
									<td align="right">0</td>
									<td align="right">{{ labaKotorPesanans.laba_jual | pemisahTitik }}</td>

								</tr>

								<tr style="color:red">

									<td>TOTAL</td>
									<td></td>
									<td></td>
									<td align="right">{{ subtotalLabaKotorPesanan.subtotal_penjualan | pemisahTitik }}</td>
									<td align="right">{{ subtotalLabaKotorPesanan.subtotal_hpp | pemisahTitik }}</td>
									<td align="right">{{ subtotalLabaKotorPesanan.subtotal_laba_kotor | pemisahTitik }}</td>
									<td align="right">{{ subtotalLabaKotorPesanan.subtotal_potongan | pemisahTitik }}</td>
									<td align="right">{{ subtotalLabaKotorPesanan.subtotal_laba_jual | pemisahTitik }}</td>

								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="labaKotorPesanan.length == 0 && loadingPesanan == false">
								<tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>
					</div><!--RESPONSIVE-->
						<vue-simple-spinner v-if="loadingPesanan"></vue-simple-spinner>
						<div align="right"><pagination :data="labaKotorPesananData" v-on:pagination-change-page="prosesLaporanPesanan" :limit="4"></pagination></div>
				</div>
			</div>
		</div>
	</div>
	

</template>


<script>
export default {
	data: function () {
		return {
			pelanggan: [],
			labaKotor: [],
			labaKotorData: {},
			subtotalLabaKotor: {},
			labaKotorPesanan: [],
			labaKotorPesananData: {},
			subtotalLabaKotorPesanan: {},
			filter: {
				dari_tanggal: '',
				sampai_tanggal: '',
				pelanggan: '',
            },
			url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-laba-kotor"),
			pencarian_pos: '',
			pencarian_pesanan: '',
            placeholder_pelanggan: {
                placeholder: '--PILIH PELANGGAN--'
            },
			loading: false,
			loadingPesanan: false,
		}
	},
	mounted() {
		var app = this;
		app.dataPelanggan();
	},
	watch: {
        // whenever question changes, this function will run
        pencarian_pos: function (newQuestion) {
        	this.getHasilPencarianPos();
        },
        pencarian_pesanan: function (newQuestion) {
        	this.getHasilPencarianPesanan();
        }
    },
    filters: {
	  pemisahTitik: function (value) {
	    return new Intl.NumberFormat().format(value)
	  },
	  tanggal: function (value) {
	    return moment(String(value)).format('DD/MM/YYYY hh:mm')
	  }
	},
    methods: {
    	submitLabaKotor(){
    		var app = this;
    		app.prosesLaporan();
    		app.prosesLaporanPesanan();
    		app.totalLabaKotor();
    		app.totalLabaKotorPesanan();
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
    			app.labaKotor = resp.data.data;
    			app.labaKotorData = resp.data;
    			app.loading = false
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Laba Kotor Penjualan /Pelanggan");
    		});
    	},
    	prosesLaporanPesanan(page) {
    		var app = this;	
    		var newFilter = app.filter;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		app.loadingPesanan = true,
    		axios.post(app.url+'/view-pesanan?page='+page, newFilter)
    		.then(function (resp) {
    			app.labaKotorPesanan = resp.data.data;
    			app.labaKotorPesananData = resp.data;
    			app.loadingPesanan = false
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Laba Kotor Penjualan /Pelanggan");
    		});
    	},
    	getHasilPencarianPos(page){
    		var app = this;
    		var newFilter = app.filter;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.post(app.url+'/pencarian?search='+app.pencarian_pos+'&page='+page, newFilter)
    		.then(function (resp) {
    			app.labaKotor = resp.data.data;
    			app.labaKotorData = resp.data;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Laba Kotor Penjualan /Pelanggan");
    		});
    	},
    	getHasilPencarianPesanan(page){
    		var app = this;
    		var newFilter = app.filter;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.post(app.url+'/pencarian-pesanan?search='+app.pencarian_pesanan+'&page='+page, newFilter)
    		.then(function (resp) {
    			app.labaKotorPesanan = resp.data.data;
    			app.labaKotorPesananData = resp.data;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Laba Kotor Penjualan /Pelanggan");
    		});
    	},
        dataPelanggan() {
          var app = this;
          axios.get(app.url+'/pilih-pelanggan')
          .then(function (resp) {
            app.pelanggan = resp.data;

        })
          .catch(function (resp) {
            alert("Tidak bisa memuat pelanggan ");
        });
      },
      totalLabaKotor() {
    		var app = this;	
    		var newFilter = app.filter;

    		app.loading = true,
    		axios.post(app.url+'/subtotal-laba-kotor', newFilter)
    		.then(function (resp) {
    			app.subtotalLabaKotor = resp.data;
    			app.loading = false
    			console.log(resp.data)
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Subtotal Laba Kotor");
    		});
    	},
      totalLabaKotorPesanan() {
    		var app = this;	
    		var newFilter = app.filter;

    		app.loading = true,
    		axios.post(app.url+'/subtotal-laba-kotor-pesanan', newFilter)
    		.then(function (resp) {
    			app.subtotalLabaKotorPesanan = resp.data;
    			app.loadingPesanan = false
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Subtotal Laba Kotor");
    		});
    	}
    }
}
</script>