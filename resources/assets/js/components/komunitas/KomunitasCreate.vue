<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexKomunitas'}">Komunitas</router-link></li>
				<li class="active">Tambah Komunitas</li>
			</ul>
			<div class="card">

				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">people</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Komunitas </h4>
					<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
						<div class="form-group">
							<label for="no_telp" class="col-md-2 control-label">No. Telp</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="No. Telp" v-model="komunitas.no_telp" type="number" name="no_telp" id="no_telp"  autofocus="">
								<span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-md-2 control-label">Nama Komunitas</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Komunitas"  v-model="komunitas.name"  type="text" name="name" id="name"   autofocus="">
								<span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="name_penggiat" class="col-md-2 control-label">Nama Penggiat</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Penggiat"  v-model="komunitas.name_penggiat"  type="text"  name="name_penggiat"  id="name_penggiat"  autofocus="">
								<span v-if="errors.name_penggiat" id="name_penggiat_error" class="label label-danger">{{ errors.name_penggiat[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label">Email</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Email" v-model="komunitas.email" type="email" name="email"  id="email" autofocus="">
								<span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="id_warung" class="col-md-2 control-label ">Warung</label>
							<div class="col-md-4">

								<selectize-component v-model="komunitas.id_warung" :settings="settings" id="pilih_id_warung"> 
									<option v-for="warungs, index in warung" v-bind:value="warungs.id" >{{ warungs.name }}</option>
								</selectize-component>
								<span v-if="errors.id_warung" id="id_warung_error" class="label label-danger">{{ errors.id_warung[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="alamat" class="col-md-2 control-label">Alamat</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Alamat" type="text" name="alamat" id="alamat" v-model="komunitas.alamat" autofocus="">
								<span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="alamat_penggiat" class="col-md-2 control-label">Alamat Penggiat</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Alamat Penggiat" type="text" name="alamat_penggiat" id="alamat_penggiat"  v-model="komunitas.alamat_penggiat"   autofocus="">
								<span v-if="errors.alamat_penggiat" id="alamat_penggiat_error" class="label label-danger">{{ errors.alamat_penggiat[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="kelurahan" class="col-md-2 control-label ">Kelurahan</label>
							<div class="col-md-4">

								<selectize-component  v-model="komunitas.kelurahan" :settings="settings" id="pilih_kelurahan"> 
									<option v-for="kelurahans, index in kelurahan" v-bind:value="kelurahans.id" >{{ kelurahans.nama }}</option>
								</selectize-component>
								<span v-if="errors.kelurahan" id="kelurahan_error" class="label label-danger">{{ errors.kelurahan[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="nama_bank" class="col-md-2 control-label">Nama Bank</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Bank" type="text" name="nama_bank" id="nama_bank" v-model="komunitas.nama_bank"  autofocus="">
								<span v-if="errors.nama_bank" id="nama_bank_error" class="label label-danger">{{ errors.nama_bank[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="no_rekening" class="col-md-2 control-label">No. Rekening</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="No. Rekening" type="number" name="no_rekening"  id="no_rekening"   v-model="komunitas.no_rekening"  autofocus="">
								<span v-if="errors.no_rekening" id="no_rekening_error" class="label label-danger">{{ errors.no_rekening[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="an_rekening" class="col-md-2 control-label">A.N Rekening</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="A.N Rekening" type="text" name="an_rekening" id="an_rekening"  v-model="komunitas.an_rekening"  autofocus="">
								<span v-if="errors.an_rekening" id="an_rekening_error" class="label label-danger">{{ errors.an_rekening[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4 col-md-offset-2">
								<button class="btn btn-primary" id="btnSimpanKomunitas" type="submit"><i class="material-icons">send</i> Submit</button>
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
			warung: [],
			kelurahan: [],
			url : window.location.origin+(window.location.pathname).replace("dashboard", "komunitas"),
			komunitas: {
				no_telp : '',
				name : '',
				name_penggiat : '',
				email : '',
				id_warung : '',
				alamat : '',
				alamat_penggiat : '',
				kelurahan : '',
				nama_bank : '',
				no_rekening : '',
				an_rekening : ''
			},
			message : '',
			settings: {
				placeholder: 'Silahkan Pilih'
			}


		}

	},
	mounted() {
		var app = this;
		app.selected_warung();
		app.selected_kelurahan();

	},
	methods: {
		saveForm() {
			var app = this;
			var newkomunitas = app.komunitas;
			axios.post(app.url, newkomunitas)
			.then(function (resp) {
				app.message = 'Berhasil Menambah Komunitas '+ app.komunitas.name;
				app.alert(app.message);
				app.komunitas.no_telp = ''
				app.komunitas.name = ''
				app.komunitas.name_penggiat = ''
				app.komunitas.email = ''
				app.komunitas.id_warung = ''
				app.komunitas.alamat = ''
				app.komunitas.alamat_penggiat = ''
				app.komunitas.kelurahan = ''
				app.komunitas.nama_bank = ''
				app.komunitas.no_rekening = ''
				app.komunitas.an_rekening = ''
				app.errors = '';
				app.$router.replace('/komunitas/');

			})
			.catch(function (resp) {
				app.success = false;
				app.errors = resp.response.data.errors;
			});
		},
		selected_warung() {
			var app = this;
			axios.get(app.url+'/warung-komunitas')
			.then(function (resp) {
				app.warung = resp.data;
			})
			.catch(function (resp) {
				alert("Could not load users");
			});
		},
		selected_kelurahan() {
			var app = this;
			axios.get(app.url+'/kelurahan-komunitas')
			.then(function (resp) {
				app.kelurahan = resp.data;
			})
			.catch(function (resp) {
				alert("Could not load users");
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