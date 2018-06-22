<template>
	<div class="row" >
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href=" ">Home</a></li>
				<li><router-link :to="{name: 'indexDaftarUserWarung'}">User</router-link></li>
				<li class="active">Edit User</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">dns</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> User </h4>
					<div>
						<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
							<div class="form-group">
								<label for="name" class="col-md-2 control-label">Nama</label>
								<div class="col-md-4">
									<input class="form-control" required autocomplete="off" placeholder="Nama" type="text" v-model="user.name" name="name"  autofocus="">
									<span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>

								</div>
							</div>
							<div class="form-group">
								<label for="no_telp" class="col-md-2 control-label">No Telp</label>
								<div class="col-md-4">
									<input class="form-control" required autocomplete="off" placeholder="No Telp" type="text" v-model="user.no_telp" name="no_telp"  autofocus="">
									<span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>

								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Email</label>
								<div class="col-md-4">
									<input class="form-control" required autocomplete="off" placeholder="Email" type="email" v-model="user.email" name="email"  autofocus="">
									<span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>

								</div>
							</div>
							<div class="form-group">
								<label for="alamat" class="col-md-2 control-label">Alamat</label>
								<div class="col-md-4">
									<input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-model="user.alamat" name="alamat"  autofocus="">
									<span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>

								</div>
							</div>
							<div class="form-group">
								<label for="role_id" class="col-md-2 control-label ">Otoritas</label>
								<div class="col-md-4">
									<selectize-component v-model="user.role_id" :settings="settings" name="role_id" id="role_id"> 
										<option v-for="otor, index in otoritas" v-bind:value="otor.id">{{ otor.display_name }}</option>
									</selectize-component>
									<span v-if="errors.role_id" id="role_id_error" class="label label-danger">{{ errors.role_id[0] }}</span>
								</div>
							</div>

							<input class="form-control" required autocomplete="off" placeholder="Role Lama" type="hidden" v-model="user.role_lama" name="role_lama" id="role_lama"  autofocus="">

							<div class="form-group">
								<div class="col-md-4 col-md-offset-2">
									<button class="btn btn-primary" id="btnSimpanUser" type="submit"><i class="material-icons">send</i> Submit</button>
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
			userId: null,
			user: {
				name : '',
				no_telp : '',
				email : '',
				alamat : '',
				role_id : '',
				role_lama : '',
			},
			message : '',
			settings: {
				placeholder: 'Pilih Otoritas'
			}, 
			url : window.location.origin+(window.location.pathname).replace("dashboard", "daftar-user-warung"),
			errors: []
		}
	},
	mounted() {
		let app = this;
		let id = app.$route.params.id;
		app.userId = id;

		axios.get(app.url+'/' + id)
		.then(function (resp) {
			app.user = resp.data.user;
			app.otoritas = resp.data.otoritas;
			app.user.role_id = resp.data.user.otoritas.role_id;
			app.user.role_lama = resp.data.user.otoritas.role_id;
		})
		.catch(function () {
			alert("Tidak dapat memuat data user")
		});
	},
	methods: {
		saveForm() {
			var app = this;
			var newUser = app.user;
			axios.put(app.url+'/' + app.userId, newUser)
			.then(function (resp) {
				app.message = 'Sukses : Berhasil Mengubah User';
				app.alert(app.message);
				app.$router.replace('/daftar-user/');
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
				buttons: false,
				timer: 1000,
			});
		}
	}
}
</script>