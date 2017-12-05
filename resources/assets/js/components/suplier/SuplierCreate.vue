<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexSuplier'}">Supplier</router-link></li>
				<li class="active">Tambah Supplier</li>
			</ul>
			<div class="card">

				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">assignment_return</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Supplier </h4>
					<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
						<div class="form-group">
							<label for="nama_suplier" class="col-md-2 control-label">Nama Supplier</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Supplier" v-model="suplier.nama_suplier" type="text" name="nama_suplier" id="nama_suplier"  autofocus="">
								<span v-if="errors.nama_suplier" id="nama_suplier_error" class="label label-danger">{{ errors.nama_suplier[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="alamat" class="col-md-2 control-label">Alamat</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Alamat Supplier" v-model="suplier.alamat" type="text" name="alamat" id="alamat"  autofocus="">
								<span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="no_telp" class="col-md-2 control-label">No. Telpon</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="No. Telpon Supplier" v-model="suplier.no_telp" type="text" name="no_telp" id="no_telp"  autofocus="">
								<span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="contact_person" class="col-md-2 control-label">Contact Person</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Contact Person Supplier" v-model="suplier.contact_person" type="text" name="contact_person" id="contact_person"  autofocus="">
								<span v-if="errors.contact_person" id="contact_person_error" class="label label-danger">{{ errors.contact_person[0] }}</span>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-4 col-md-offset-2">
								<button class="btn btn-primary" id="btnSimpanSuplier" type="submit"><i class="material-icons">send</i> Submit</button>
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
			url : window.location.origin+(window.location.pathname).replace("dashboard", "suplier"),
			suplier: {
				nama_suplier : '',
				alamat : '',
				no_telp : '',
				contact_person : '',
			},
			message : ''
		}
	},
	methods: {
		saveForm() {
			var app = this;
			var newsuplier = app.suplier;

			axios.post(app.url, newsuplier)
			.then(function (resp) {
				app.message = 'Menambah Suplier '+ app.suplier.nama_suplier;
				app.alert(app.message);
				app.suplier.nama_suplier = '';
				app.suplier.alamat = '';
				app.suplier.no_telp = '';
				app.suplier.contact_person = '';
				app.errors = '';
				app.$router.replace('/suplier/');

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