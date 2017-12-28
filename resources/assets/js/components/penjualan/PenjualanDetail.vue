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
				<li><router-link :to="{name: 'indexPenjualan'}">Penjualan</router-link></li>
				<li class="active">Detail Penjualan</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Detail Penjualan </h4>
					<div class="toolbar">
						<p> <router-link :to="{name: 'indexPenjualan'}" class="btn btn-primary">Kembali</router-link></p>
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

											<th>No Transaksi</th>
											<th>Produk</th>
											<th>Jumlah</th>
											<th>Harga</th>
											<th>Potongan</th>
											<th>Subtotal</th>

										</tr>
									</thead>
									<tbody v-if="detailPenjualan.length"  class="data-ada">
										<tr v-for="detailPenjualan, index in detailPenjualan" >

											<td>{{ detailPenjualan.id_penjualan_pos }}</td>
											<td>{{ detailPenjualan.nama_produk }}</td>
											<td> {{ new Intl.NumberFormat().format(detailPenjualan.jumlah_produk) }},00</td>
											<td> {{ new Intl.NumberFormat().format(detailPenjualan.harga_produk) }},00</td>
											<td> {{ new Intl.NumberFormat().format(detailPenjualan.potongan) }},00</td>
											<td> {{ new Intl.NumberFormat().format(detailPenjualan.subtotal) }},00</td>

										</tr>
									</tbody>                    
									<tbody class="data-tidak-ada" v-else>
										<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
									</tbody>
								</table>    

								<vue-simple-spinner v-if="loading"></vue-simple-spinner>

								<div align="right"><pagination :data="detailPenjualanData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="card card-stats">
								<div class="card-header" data-background-color="blue">
									<i class="material-icons">shopping_cart</i>
								</div>
								<div class="card-content">
									<p class="category">Total Keseluruhan</p>
									<h3 class="card-title">{{ new Intl.NumberFormat().format(subtotal) }},00</h3>
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
			detailPenjualan: [],
			detailPenjualanData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
			pencarian: '',
			loading: true,
			seen : false,    
			subtotal : 0,         
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
		axios.get(app.url+'/view-detail-penjualan/'+id+'?page='+page)
		.then(function (resp) {
			app.detailPenjualan = resp.data.data;
			app.detailPenjualanData = resp.data;
			app.loading = false;
			app.seen = true;

			$.each(resp.data.data, function (i, item) {
				app.subtotal += resp.data.data[i].subtotal 
			});
		})
		.catch(function (resp) {
			console.log(resp);
			app.loading = false;
			app.seen = true;
			alert("Tidak Dapat Memuat Detail Penjualan");
		});
	}, 
	getHasilPencarian(page){
		var app = this;
		var id = app.$route.params.id;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/pencarian-detail-penjualan/'+id+'?search='+app.pencarian+'&page='+page)
		.then(function (resp) {
			app.detailPenjualan = resp.data.data;
			app.detailPenjualanData = resp.data;
			app.loading = false;
			app.seen = true;
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Detail Penjualan");
		});
	}
}
}
</script>

