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
				<li><router-link :to="{name: 'indexPembelian'}">Pembelian</router-link></li>
				<li class="active">Detail Pembelian</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Detail Pembelian {{no_faktur}}</h4>
					<div class="toolbar">
						<p> <router-link :to="{name: 'indexPembelian'}" class="btn btn-primary">Kembali</router-link></p>
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
											<th style="text-align:right;">Harga</th>
											<th style="text-align:right;">Potongan</th>
											<th style="text-align:right;">Tax</th>
											<th style="text-align:right;">Subtotal</th>

										</tr>
									</thead>
									<tbody v-if="detailPembelian.length"  class="data-ada">
										<tr v-for="detailPembelian, index in detailPembelian" >

											<td>{{ detailPembelian.kode_produk }} - {{ detailPembelian.nama_produk }}</td>
											<td style="text-align:right;"> {{ detailPembelian.jumlah_produk_pemisah }}</td>
											<td style="text-align:right;"> {{ detailPembelian.harga_pemisah }}</td>
											<td style="text-align:right;">{{ Math.round(detailPembelian.potongan,2) }} | {{ Math.round(detailPembelian.potongan_persen,2) }} %</td>
											<td style="text-align:right;"> {{ Math.round(detailPembelian.tax,2) }} | {{ Math.round(detailPembelian.tax_persen, 2) }} %</td>
											<td style="text-align:right;"> {{ detailPembelian.subtotal_tbs }} </td>
										</tr>
									</tbody>                    
									<tbody class="data-tidak-ada" v-else>
										<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
									</tbody>
								</table>    

								<vue-simple-spinner v-if="loading"></vue-simple-spinner>

								<div align="right"><pagination :data="detailPembelianData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="card card-stats">
								<div class="card-header" data-background-color="blue">
									<i class="material-icons">shopping_cart</i>
								</div>
								<div class="card-content">
									<h3>Total Keseluruhan</h3>
             						 <h3 class="card-title"><money v-bind="separator" name="subtotal"  id="subtotal" autocomplete="off"  style="text-align:right;" readonly="" v-model="subtotal"></money></h3>
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
			detailPembelian: [],
			detailPembelianData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian"),
			      separator: {
              decimal: ',',
              thousands: '.',
              prefix: '',
              suffix: '',
              precision: 2,
              masked: false /* doesn't work with directive */
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
    // whenever question changes, this function will run
    pencarian: function (newQuestion) {
    	this.getHasilPencarian()
    	this.loading = true
    }
},
methods: {
	getResults(page) {
		var app = this; 
		var id = app.$route.params.id;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/view-detail-pembelian/'+id+'?page='+page)
		.then(function (resp) {
			app.detailPembelian = resp.data.data;
			app.detailPembelianData = resp.data;
			app.no_faktur = resp.data.no_faktur;
			app.loading = false;
			app.seen = true;
			$.each(resp.data.data, function (i, item) {
				app.subtotal += parseFloat(resp.data.data[i].subtotal) 
			});
		})
		.catch(function (resp) {
			console.log(resp);
			app.loading = false;
			app.seen = true;
			alert("Tidak Dapat Memuat Detail Pembelian");
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
			app.detailPembelian = resp.data.data;
			app.detailPembelianData = resp.data;
			app.loading = false;
			app.seen = true;
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Detail Pembelian");
		});
	}
}
}
</script>

