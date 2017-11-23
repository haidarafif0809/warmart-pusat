<template>
	
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Ubah Profil</li>
			</ul>


			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">account_circle</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Ubah Profil </h4>
					<div class="toolbar">

						<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
							<div class="form-group">
								<label for="nama" class="col-md-2 control-label">Nama</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Nama" v-model="user.nama" type="text" name="nama" id="nama"  autofocus="">
									<span v-if="errors.nama" id="nama_error" class="label label-danger">{{ errors.nama[0] }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="no_telp" class="col-md-2 control-label">No. Telp</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="No. Telp" v-model="user.no_telp" type="number" name="no_telp" id="no_telp"  autofocus="">
									<span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Email</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Email" v-model="user.email" type="email" name="email" id="email"  autofocus="">
									<span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="alamat" class="col-md-2 control-label">Alamat</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Alamat" v-model="user.alamat" type="text" name="alamat" id="alamat"  autofocus="">
									<span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
								</div>
							</div>		

							<input class="form-control" autocomplete="off" v-model="user.id" type="hidden" name="id" id="id"  autofocus="">
							<div class="form-group">
								<div class="col-md-4 col-md-offset-2">
									<button class="btn btn-primary" id="btnUbahProfil" type="submit"><i class="material-icons">send</i> Submit</button>
								</div>
							</div>
						</form>

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
			user: {
				id : '',
				nama : '',
				no_telp : '',
				email : '',
				alamat : ''
			},
			message : '',
			url : window.location.origin+(window.location.pathname).replace("dashboard", "ubah-profil-admin"),
			errors: []
		}
	},
	mounted() {
		let app = this;		
		axios.get(app.url)
		.then(function (resp) {
			app.user = resp.data;
		})
		.catch(function () {
			alert("Tidak dapat memuat Profil Admin")
		});
	},
	methods: {        
		saveForm() {
			var app = this;
			var newuser = app.user;
			axios.put('proses-ubah-profil-admin', newuser)
			.then(function (resp) {
				app.message = 'Berhasil Mengubah Profil '+app.user.nama;
				app.alert(app.message);
				app.$router.replace('/');
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