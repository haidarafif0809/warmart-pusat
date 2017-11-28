<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexKategoriTransaksi'}">Kategori Transaksi</router-link></li>
				<li class="active">Edit Kategori Transaksi</li>
			</ul>
			<div class="card">

				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">local_offer</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Kategori Transaksi </h4>
					<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
						<div class="form-group">
							<label for="nama_kategori_transaksi" class="col-md-2 control-label">Nama Kategori Transaksi</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Kategori Transaksi" v-model="kategoriTransaksi.nama_kategori_transaksi" type="text" name="nama_kategori_transaksi" id="nama_kategori_transaksi"  autofocus="">
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

	mounted() {
		let app = this;
		let id = app.$route.params.id;
		app.kategoriTransaksiId = id;
		
		axios.get(app.url+'/' + id)
		.then(function (resp) {
			app.kategoriTransaksi = resp.data;
		})
		.catch(function () {
			alert("Tidak dapat memuat Kategori Transaksi")
		});
	},	
	data: function () {
		return {

			kategoriTransaksiId: null,
			kategoriTransaksi: {
				nama_kategori_transaksi : '',
			},
			message : '',
			url : window.location.origin+(window.location.pathname).replace("dashboard", "kategori-transaksi"),
			errors: []
		}
	},
	methods: {        
		saveForm() {
			var app = this;
			var newkategoriTransaksi = app.kategoriTransaksi;
			axios.patch(app.url+'/' + app.kategoriTransaksiId, newkategoriTransaksi)
			.then(function (resp) {
				app.message = 'Berhasil Mengubah Kategori Transaksi '+app.kategoriTransaksi.nama_kategori_transaksi;
				app.alert(app.message);
				app.$router.replace('/kategori-transaksi/');
			})
			.catch(function (resp) {
				console.log(resp);
				app.errors = resp.response.data.errors;
				alert("Periksa kembali data yang anda masukan");
			});
		},
		alert(pesan) {
			this.$swal({
				title: "Berhasil !",
				text: pesan,
				icon: "success",
			});
		}
	}
}
</script>