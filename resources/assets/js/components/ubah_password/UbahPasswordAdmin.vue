<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Ubah Password</li>
			</ul>


			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">lock_outline</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Ubah Password </h4>
					<div class="toolbar">
						<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
							<div class="form-group">
								<label for="password" class="col-md-2 control-label">Password Baru</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Password Baru" v-model="user.password" type="password" name="password" id="password"  autofocus="">
									<span v-if="errors.password" id="password_error" class="label label-danger">{{ errors.password[0] }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="password_confirmation" class="col-md-2 control-label">Konfirmasi Password</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Konfirmasi Password" v-model="user.password_confirmation" type="password" name="password_confirmation" id="password_confirmation"  autofocus="">
									<span v-if="errors.password_confirmation" id="password_confirmation_error" class="label label-danger">{{ errors.password_confirmation[0] }}</span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-4 col-md-offset-2">
									<button class="btn btn-primary" id="btnUbahPassword" type="submit"><i class="material-icons">send</i> Ubah Password</button>
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
			errors: [],
			url : window.location.origin+(window.location.pathname).replace("dashboard", "ubah-password-admin"),
			user: {
				password : '',
				password_confirmation : ''
			}
		}

	},
	methods: {
		saveForm() {
			var app = this;
			var newuser = app.user;
			axios.put('proses-ubah-password', newuser)
			.then(function (resp) {
				app.alert();
				app.errors = '';
				app.$router.replace('/');

			})
			.catch(function (resp) {
				app.success = false;
				app.errors = resp.response.data.errors;
			});
		},
		alert(pesan) {
			this.$swal({
				title: "Berhasil!",
				text: "Password Berhasil Diubah",
				icon: "success",
			});
		}
	}
}
</script>