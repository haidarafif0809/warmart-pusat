<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexSuplier'}">Suplier</router-link></li>
				<li class="active">Edit Suplier</li>
			</ul>
			<div class="card">

				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">local_offer</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Suplier </h4>
					<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
						<div class="form-group">
							<label for="nama_suplier" class="col-md-2 control-label">Nama Suplier</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Suplier" v-model="suplier.nama_suplier" type="text" name="nama_suplier" id="nama_suplier"  autofocus="">
								<span v-if="errors.nama_suplier" id="nama_suplier_error" class="label label-danger">{{ errors.nama_suplier[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="alamat" class="col-md-2 control-label">Alamat</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Alamat Suplier" v-model="suplier.alamat" type="text" name="alamat" id="alamat"  autofocus="">
								<span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="no_telp" class="col-md-2 control-label">No. Telpon</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="No. Telpon Suplier" v-model="suplier.no_telp" type="text" name="no_telp" id="no_telp"  autofocus="">
								<span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="contact_person" class="col-md-2 control-label">Contact Person</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Contact Person Suplier" v-model="suplier.contact_person" type="text" name="contact_person" id="contact_person"  autofocus="">
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

	mounted() {
		let app = this;
		let id = app.$route.params.id;
		app.suplierId = id;
		
		axios.get(app.url+'/' + id)
		.then(function (resp) {
			app.suplier = resp.data;
		})
		.catch(function () {
			alert("Tidak Dapat Memuat Suplier")
		});
	},	
	data: function () {
		return {

			suplierId: null,
			suplier: {
				nama_suplier : '',
				alamat : '',
				no_telp : '',
				contact_person : '',
			},
			message : '',
			url : window.location.origin+(window.location.pathname).replace("dashboard", "suplier"),
			errors: []
		}
	},
	methods: {        
		saveForm() {
			var app = this;
			var newsuplier = app.suplier;
			axios.patch(app.url+'/' + app.suplierId, newsuplier)
			.then(function (resp) {
				app.message = 'Berhasil Mengubah Suplier '+app.suplier.nama_suplier;
				app.alert(app.message);
				app.$router.replace('/suplier/');
			})
			.catch(function (resp) {				
				console.log(resp)
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