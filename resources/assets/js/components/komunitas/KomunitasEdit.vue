<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexKomunitas'}">Komunitas</router-link></li>
				<li class="active">Edit Komunitas</li>
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
								<input class="form-control" autocomplete="off" placeholder="No. Telp" v-model="komunitas.no_telp" type="number" name="no_telp"  autofocus="">
								<span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-md-2 control-label">Nama Komunitas</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Komunitas"  v-model="komunitas.name"  type="text" name="name"  autofocus="">
								<span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="name_penggiat" class="col-md-2 control-label">Nama Penggiat</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Penggiat"  v-model="komunitas.name_penggiat"  type="text" name="name_penggiat"  autofocus="">
								<span v-if="errors.name_penggiat" id="name_penggiat_error" class="label label-danger">{{ errors.name_penggiat[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-2 control-label">Email</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Email" v-model="komunitas.email" type="email" name="email"  autofocus="">
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
								<input class="form-control" autocomplete="off" placeholder="Alamat" type="text" name="alamat" v-model="komunitas.alamat" autofocus="">
								<span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="alamat_penggiat" class="col-md-2 control-label">Alamat Penggiat</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Alamat Penggiat" type="text" name="alamat_penggiat"  v-model="komunitas.alamat_penggiat"   autofocus="">
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
								<input class="form-control" autocomplete="off" placeholder="Nama Bank" type="text" name="nama_bank" v-model="komunitas.nama_bank"  autofocus="">
								<span v-if="errors.nama_bank" id="nama_bank_error" class="label label-danger">{{ errors.nama_bank[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="no_rekening" class="col-md-2 control-label">No. Rekening</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="No. Rekening" type="number" name="no_rekening"  v-model="komunitas.no_rekening"  autofocus="">
								<span v-if="errors.no_rekening" id="no_rekening_error" class="label label-danger">{{ errors.no_rekening[0] }}</span>
							</div>
						</div>

						<div class="form-group">
							<label for="an_rekening" class="col-md-2 control-label">A.N Rekening</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="A.N Rekening" type="text" name="an_rekening"  v-model="komunitas.an_rekening"  autofocus="">
								<span v-if="errors.an_rekening" id="an_rekening_error" class="label label-danger">{{ errors.an_rekening[0] }}</span>
							</div>
						</div>

						<div class="form-group"  style="display: none;"> 
							<label for="id_bank" class="col-md-2 control-label">ID BANK</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" type="text" name="id_bank"  v-model="komunitas.id_bank"  autofocus="">
								<span v-if="errors.id_bank" id="id_bank_error" class="label label-danger">{{ errors.id_bank[0] }}</span>
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

	mounted() {
		let app = this;
		let id = app.$route.params.id;
		app.komunitasId = id;

		app.selected_warung();
		app.selected_kelurahan();
		
		axios.get(app.url+'/' + id)
		.then(function (resp) {
			app.komunitas = resp.data;
		})
		.catch(function () {
			alert("Tidak dapat memuat komunitas")
		});
	},	
	data: function () {
		return {

			warung: [],
			kelurahan: [],
			komunitasId: null,
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
				an_rekening : '',
				id_bank : ''
			},
			message : '',
			settings: {
				placeholder: 'Silahkan Pilih'
			}, 
			url : window.location.origin+(window.location.pathname).replace("dashboard", "komunitas"),
			errors: []
		}
	},
	methods: {        
		saveForm() {
			var app = this;
			var newkomunitas = app.komunitas;
			axios.patch(app.url+'/' + app.komunitasId, newkomunitas)
			.then(function (resp) {
				app.message = 'Berhasil Mengubah Komunitas '+app.komunitas.name;
				app.alert(app.message);
				app.$router.replace('/komunitas/');
			})
			.catch(function (resp) {
				console.log(resp);
				app.errors = resp.response.data.errors;
				alert("Periksa kembali data yang anda masukan");
			});
		},
		selected_warung() {
			var app = this;
			axios.get(app.url+'/warung-komunitas')
			.then(function (resp) {
				app.warung = resp.data;
			})
			.catch(function (resp) {
				alert("Tidak dapat memuat komunitas");
			});
		},
		selected_kelurahan() {
			var app = this;
			axios.get(app.url+'/kelurahan-komunitas')
			.then(function (resp) {
				app.kelurahan = resp.data;
			})
			.catch(function (resp) {
				alert("Tidak dapat memuat  komunitas");
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