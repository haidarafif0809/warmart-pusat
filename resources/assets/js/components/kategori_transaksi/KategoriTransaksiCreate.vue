<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexKategoriTransaksi'}">Kategori Transaksi</router-link></li>
				<li class="active">Tambah Kategori Transaksi</li>
			</ul>
			<div class="card">

				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">local_offer</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Kategori Transaksi </h4>
					<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
						<div class="form-group">
							<label for="nama_kategori_transaksi" class="col-md-2 control-label">Nama Kategori</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Kategori Transaksi" v-model="kategori_transaksi.nama_kategori_transaksi" type="text" name="nama_kategori_transaksi" id="nama_kategori_transaksi"  autofocus="">
								<span v-if="errors.nama_kategori_transaksi" id="nama_kategori_transaksi_error" class="label label-danger">{{ errors.nama_kategori_transaksi[0] }}</span>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-4 col-md-offset-2">
								<button class="btn btn-primary" id="btnSimpanKategoriTransaksi" type="submit"><i class="material-icons">send</i> Submit</button>
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
			url : window.location.origin+(window.location.pathname).replace("dashboard", "kategori-transaksi"),
			kategori_transaksi: {
				nama_kategori_transaksi : '',
			},
			message : ''
		}
	},
	methods: {
		saveForm() {
			var app = this;
			var newkategori_transaksi = app.kategori_transaksi;
			console.log(newkategori_transaksi)
			axios.post(app.url, newkategori_transaksi)
			.then(function (resp) {
				app.message = 'Menambah Kategori Transaksi '+ app.kategori_transaksi.nama_kategori_transaksi;
				app.alert(app.message);
				app.kategori_transaksi.nama_kategori_transaksi = ''
				app.errors = '';
				app.$router.replace('/kategori-transaksi/');

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