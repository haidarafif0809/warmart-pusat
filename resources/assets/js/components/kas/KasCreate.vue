<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexKas'}">Kas</router-link></li>
				<li class="active">Tambah Kas</li>
			</ul>
			<div class="card">

				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">payment</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Kas </h4>
					<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
						<div class="form-group">
							<label for="kode_kas" class="col-md-2 control-label">Kode Kas</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Kode Kas" v-model="kas.kode_kas" type="text" name="kode_kas" id="kode_kas"  autofocus="">
								<span v-if="errors.kode_kas" id="kode_kas_error" class="label label-danger">{{ errors.kode_kas[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="nama_kas" class="col-md-2 control-label">Nama Kas</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Kas" v-model="kas.nama_kas" type="text" name="nama_kas" id="nama_kas"  autofocus="">
								<span v-if="errors.nama_kas" id="nama_kas_error" class="label label-danger">{{ errors.nama_kas[0] }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="nama_kas" class="col-md-2 control-label">Tampil Ditransaksi</label>
							<div class="col-md-4">
									<toggle-button  :value="1" id="status_kas" name="status_kas" v-model="kas.status_kas" :sync="false"/>
							</div>
						</div>
						<div class="form-group">
							<label for="default_kas" class="col-md-2 control-label">Default Kas</label>
							<div class="col-md-4">
								<toggle-button v-on:change="defaultKas()" :value="1" id="default_kas" name="default_kas" v-model="kas.default_kas" :sync="false"/>
							</div>
						</div>

						
						<div class="form-group">
							<div class="col-md-4 col-md-offset-2">
									<p style="color: red; font-style: italic;">*Note : Hanya 1 Kas yang dijadikan default.</p>
								<button class="btn btn-primary" id="btnSimpanKas" type="submit"><i class="material-icons">send</i> Submit</button>
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
			url : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
			kas: {
				kode_kas : '',
				nama_kas : '',
				status_kas : 0,
				default_kas : 0
			},

			message : ''
		}
	},
	methods: {
		saveForm() {
			var app = this;
			var newkas = app.kas;
			console.log(newkas)
			axios.post(app.url, newkas)
			.then(function (resp) {
				app.message = 'Menambah Kategori Transaksi '+ app.kas.nama_kas;
				app.alert(app.message);
				app.kas.kode_kas = ''
				app.kas.nama_kas = ''
				app.kas.status_kas = 0
				app.kas.default_kas = 0
				app.errors = '';
				app.$router.replace('/kas/');

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
		},
         defaultKas() {
         var app = this;
          var toogle = app.kas.default_kas;

         if (toogle == true) {
			swal({
			title: "Konfirmasi",
			text: "Apakah Anda Yakin Ingin Mengubah Kas Utama ?",
			icon: "warning",
			buttons: true,
			dangerMode: true,
			})
			.then((confirm) => {
				if (confirm) {
				toogle : "true";
				} else {
			 	toogle : "false";
			}
			});
         } 	
      }
	}
}
</script>