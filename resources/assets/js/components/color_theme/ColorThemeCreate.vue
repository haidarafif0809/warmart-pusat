<style scoped>
	.colorpicker-2x .colorpicker-saturation {
		width: 200px;
		height: 200px;
	}

	.colorpicker-2x .colorpicker-hue,
	.colorpicker-2x .colorpicker-alpha {
		width: 30px;
		height: 200px;
	}

	.colorpicker-2x .colorpicker-color,
	.colorpicker-2x .colorpicker-color div {
		height: 30px;
	}
</style> 
<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'colorTheme'}">Tema</router-link></li>
				<li class="active">Tambah Tema</li>
			</ul>
			<div class="card">

				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">color_lens</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Tema </h4>
					<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
						<div class="form-group">
							<label for="nama_tema" class="col-md-2 control-label">Nama Tema</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" placeholder="Nama Tema" v-model="tema.nama_tema" type="text" name="nama_tema" id="nama_tema"  autofocus="">
								<span v-if="errors.nama_tema" id="nama_tema_error" class="label label-danger">{{ errors.nama_tema[0] }}</span>
							</div>
						</div>
						
						<div class="form-group">
							<label for="kode_tema" class="col-md-2 control-label">Background</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" v-model="tema.kode_tema" type="color" name="kode_tema" id="kode_tema" autofocus="">
							</div>
						</div>

						<div class="form-group">
							<label for="header_tema" class="col-md-2 control-label">Tombol Header</label>
							<div class="col-md-4">
								<input class="form-control" autocomplete="off" v-model="tema.header_tema" type="color" name="header_tema" id="header_tema" autofocus="">
							</div>
						</div>

						<div class="form-group">
							<label for="default_tema" class="col-md-2 control-label">Terapkan Tema</label>
							<div class="col-md-4 checkbox">
								<label>
									<input type="checkbox" v-model="tema.default_tema" true-value="1" false-value="0" @click="terapkanTema(tema.default_tema)"> {{tema.status}}
								</label>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-4 col-md-offset-2">
								<p style="color: red; font-style: italic;">*Note : Hanya 1 Tema Diterapkan.</p>
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
				url : window.location.origin+(window.location.pathname).replace("dashboard", "tema"),
				tema: {
					nama_tema: '',
					kode_tema: '#ffffff',
					header_tema: '#ffffff',
					default_tema: 1,
					status: 'Aktif'
				},
				message : ''
			}
		},
		methods: {
			saveForm() {
				var app = this;
				var newTema = app.tema;
				console.log(newTema)
				axios.post(app.url, newTema)
				.then(function (resp) {
					app.message = 'Menambah Tema '+ app.tema.nama_tema;
					app.alert(app.message);
					app.tema.nama_tema = ''
					app.tema.kode_tema = '#ffffff'
					app.tema.header_tema = '#ffffff'
					app.tema.default_tema = 0
					app.errors = '';

				})
				.catch(function (resp) {
					app.success = false;
					app.errors = resp.response.data.errors;
				});
			},
			terapkanTema(value){
				var app = this;
				if (value == 0)
					app.tema.status = "Aktif";
				else
					app.tema.status = "Tidak Aktif";
				
			},
			alert(pesan) {
				this.$swal({
					title: "Berhasil!",
					text: pesan,
					icon: "success",
					timer: 1500
				});
			}
		}
	}
</script>