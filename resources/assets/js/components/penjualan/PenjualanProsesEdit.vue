<style scoped>
.pencarian {
	color: red; 
	float: right;
}
.card-produk{
	background-color:#82B1FF;
}
</style>

<template>


	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb" style="margin-bottom: 1px; margin-top: 1px;">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexPenjualan'}">Lap. Penjualan</router-link></li>
				<li class="active">Edit Penjualan </li>
			</ul>

			<div class="card" style="margin-bottom: 1px; margin-top: 1px;">
				<div class="card-content">
					<h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Edit Penjualan </h4>

					<div class="row" style="margin-bottom: 1px; margin-top: 1px;">

						<div class="col-md-3">
							<div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">

								<div class="form-group" style="margin-right: 10px; margin-left: 10px;">
									<selectize-component :settings="placeholder_produk" id="produk" ref='produk'> 
										<option></option>
									</selectize-component>
								</div>  
							</div>
						</div>
					</div>

					<!--TABEL TBS ITEM  MASUK -->
					<div class="row">

						<div class="col-md-8">
							<div class=" table-responsive ">
								<div class="pencarian">
									<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
								</div>

								<vue-simple-spinner v-if="loading"></vue-simple-spinner>

							</div>
						</div>
						<div class="col-md-4">

							<div class="card card-stats">
								<div class="card-header" data-background-color="blue">
									<i class="material-icons">shopping_cart</i>
								</div>
								<div class="card-content">
									<p class="category">Subtotal</p>
									<h3 class="card-title">0,00</h3>
								</div>
								<div class="card-footer">
									<button type="button" class="btn btn-success"><i class="material-icons">payment</i>Bayar(F2)</button>
									<button type="submit" class="btn btn-danger"><i class="material-icons">cancel</i> Batal(F3) </button>
								</div>
							</div>
						</div>
					</div>
					<p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah, Harga, & Potongan Untuk Mengubah Nilai.</p>      
				</div><!-- / PANEL BODY -->

			</div>
		</div>
	</div>

</template>

<script>
export default {
	data: function () {
		return {
			errors: [],
			url : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
			placeholder_produk: {
				placeholder: 'Cari Produk (F1) ...'
			},
			pencarian: '',
			loading: true
		}
	},
	mounted() {   
		var app = this;
		app.insertEditTbsPenjualan();
	},

	methods: {
		insertEditTbsPenjualan() {
			var app = this; 			
			var id = app.$route.params.id;
			axios.get(app.url+'/'+id+'/edit')
			.then(function (resp) {
				app.$router.replace('/edit-penjualan/'+id);
			})
			.catch(function (resp) {
				console.log(resp);
				alert("Tidak Dapat Memuat Edit Penjualan");
				app.$router.replace('/penjualan');
			});
		}
	}
}
</script>
