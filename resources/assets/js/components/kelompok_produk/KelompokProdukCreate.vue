<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexKelompokProduk'}">Kelompok Produk</router-link></li>
				<li class="active">Tambah Kelompok Produk</li>
			</ul>
			<div class="card">

				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">people</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Kelompok Produk </h4>
					<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
						<div class="form-group">
							<label for="nama_kelompok" class="col-md-2 control-label">Nama</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama" v-model="kelompok_produk.nama_kelompok" type="text" name="nama_kelompok" id="nama_kelompok"  autofocus="">
								<span v-if="errors.nama_kelompok" id="nama_kelompok_error" class="label label-danger">{{ errors.nama_kelompok[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="icon_kelompok" class="col-md-2 control-label">Icon</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Icon"  v-model="kelompok_produk.icon_kelompok"  type="text" name="icon_kelompok" id="icon_kelompok" >
								<span class="label label-info"><a href="https://material.io/icons/" target="blank" >Lihat Icon</a></span> 
								<span v-if="errors.icon_kelompok" id="icon_kelompok_error" class="label label-danger">{{ errors.icon_kelompok[0] }}</span>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-4 col-md-offset-2">
								<button class="btn btn-primary" id="btnSimpanKelompokProduk" type="submit"><i class="material-icons">send</i> Submit</button>
							</div>
						</div>
					</form>
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
			url : window.location.origin+(window.location.pathname).replace("dashboard", "kelompok-produk"),
			kelompok_produk: {
				nama_kelompok : '',
				icon_kelompok : ''
			},
			message : ''
		}

	},
	methods: {
		saveForm() {
			var app = this;
			var newkelompok_produk = app.kelompok_produk;
			axios.post(app.url, newkelompok_produk)
			.then(function (resp) {
				app.message = 'Menambah Kelompok Produk '+ app.kelompok_produk.nama_kelompok;
				app.alert(app.message);
				app.kelompok_produk.nama_kelompok = ''
				app.kelompok_produk.icon_kelompok = ''
				app.errors = '';
				app.$router.replace('/kelompok-produk/');

			})
			.catch(function (resp) {
				app.success = false;
				app.errors = resp.response.data.errors;
			});
		},
		alert(pesan) {
			this.$swal({
				title: "Berhasil!",
				text: pesan,
				icon: "success",
			});
		}
	}
}
</script>